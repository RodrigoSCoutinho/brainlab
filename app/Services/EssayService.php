<?php

namespace App\Services;

use App\Models\Essay;
use App\Models\EssayAnalysis;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EssayService
{
    /**
     * Create a new essay submission.
     */
    public function createEssay(User $user, array $data): Essay
    {
        return Essay::create([
            'user_id' => $user->id,
            'title'   => $data['title'],
            'content' => $data['content'],
            'subject' => $data['subject'] ?? null,
            'status'  => 'submitted',
        ]);
    }

    /**
     * AI-powered essay analysis via OpenAI, with local fallback.
     */
    public function analyzeWithAI(Essay $essay): EssayAnalysis
    {
        $apiKey = config('services.openai.api_key');

        if ($apiKey) {
            try {
                return $this->analyzeWithOpenAI($essay, $apiKey);
            } catch (\Throwable $e) {
                Log::error('OpenAI essay analysis failed: ' . $e->getMessage());
            }
        }

        return $this->analyzeWithHeuristics($essay);
    }

    // ---------------------------------------------------------------
    // OpenAI analysis
    // ---------------------------------------------------------------

    private function analyzeWithOpenAI(Essay $essay, string $apiKey): EssayAnalysis
    {
        $model = config('services.openai.model', 'gpt-4o-mini');

        $systemPrompt = <<<PROMPT
Você é um professor de língua portuguesa do IFRN especializado em correção de redações dissertativas.
Analise a redação do aluno e retorne um JSON com exatamente este formato, sem nenhum texto extra:
{
  "competency_1": <0-200, domínio da norma culta>,
  "competency_2": <0-200, compreensão e abordagem do tema>,
  "competency_3": <0-200, argumentação e repertório>,
  "competency_4": <0-200, coesão e coerência textual>,
  "competency_5": <0-200, conclusão e proposta de solução>,
  "feedback": "<feedback detalhado em português com pontos fortes e sugestões de melhoria, mínimo 150 palavras>"
}
PROMPT;

        $userContent = "Título: {$essay->title}\n";
        if ($essay->subject) {
            $userContent .= "Tema: {$essay->subject}\n";
        }
        $userContent .= "\nRedação:\n{$essay->content}";

        $response = Http::withToken($apiKey)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userContent],
                ],
                'temperature' => 0.3,
                'response_format' => ['type' => 'json_object'],
            ]);

        $response->throw();

        $data = json_decode($response->json('choices.0.message.content'), true);

        $c1 = (int) ($data['competency_1'] ?? 0);
        $c2 = (int) ($data['competency_2'] ?? 0);
        $c3 = (int) ($data['competency_3'] ?? 0);
        $c4 = (int) ($data['competency_4'] ?? 0);
        $c5 = (int) ($data['competency_5'] ?? 0);
        $total = $c1 + $c2 + $c3 + $c4 + $c5;
        $feedback = "🤖 **Análise por IA (ChatGPT)**\n\n" . ($data['feedback'] ?? '');

        $analysis = EssayAnalysis::create([
            'essay_id'      => $essay->id,
            'analyzed_by'   => null,
            'analysis_type' => 'ai',
            'score'         => $total,
            'feedback'      => $feedback,
            'competency_1'  => $c1,
            'competency_2'  => $c2,
            'competency_3'  => $c3,
            'competency_4'  => $c4,
            'competency_5'  => $c5,
        ]);

        $essay->update(['status' => 'analyzed']);

        return $analysis;
    }

    // ---------------------------------------------------------------
    // Local heuristics fallback (no API key)
    // ---------------------------------------------------------------

    private function analyzeWithHeuristics(Essay $essay): EssayAnalysis
    {
        $wordCount  = str_word_count($essay->content);
        $paragraphs = count(array_filter(explode("\n", $essay->content), fn($p) => trim($p) !== ''));

        $c1 = $this->simulateScore($wordCount, 150, 400);
        $c2 = $this->simulateScore($wordCount, 120, 350);
        $c3 = $this->simulateScore($paragraphs, 3, 5);
        $c4 = $this->simulateScore($paragraphs, 3, 5);
        $c5 = $this->simulateScore($wordCount, 100, 300);
        $total = $c1 + $c2 + $c3 + $c4 + $c5;

        $feedback = $this->generateHeuristicFeedback($wordCount, $paragraphs, $c1, $c2, $c3, $c4, $c5);

        $analysis = EssayAnalysis::create([
            'essay_id'      => $essay->id,
            'analyzed_by'   => null,
            'analysis_type' => 'ai',
            'score'         => $total,
            'feedback'      => $feedback,
            'competency_1'  => $c1,
            'competency_2'  => $c2,
            'competency_3'  => $c3,
            'competency_4'  => $c4,
            'competency_5'  => $c5,
        ]);

        $essay->update(['status' => 'analyzed']);

        return $analysis;
    }

    // ---------------------------------------------------------------
    // Professor analysis
    // ---------------------------------------------------------------

    public function analyzeAsProfessor(Essay $essay, User $professor, array $data): EssayAnalysis
    {
        $total = ($data['competency_1'] ?? 0)
               + ($data['competency_2'] ?? 0)
               + ($data['competency_3'] ?? 0)
               + ($data['competency_4'] ?? 0)
               + ($data['competency_5'] ?? 0);

        $analysis = EssayAnalysis::create([
            'essay_id'      => $essay->id,
            'analyzed_by'   => $professor->id,
            'analysis_type' => 'professor',
            'score'         => $total,
            'feedback'      => $data['feedback'],
            'competency_1'  => $data['competency_1'] ?? 0,
            'competency_2'  => $data['competency_2'] ?? 0,
            'competency_3'  => $data['competency_3'] ?? 0,
            'competency_4'  => $data['competency_4'] ?? 0,
            'competency_5'  => $data['competency_5'] ?? 0,
        ]);

        $essay->update(['status' => 'analyzed']);

        return $analysis;
    }

    // ---------------------------------------------------------------
    // Queries
    // ---------------------------------------------------------------

    public function getUserEssays(User $user): Collection
    {
        return Essay::where('user_id', $user->id)
            ->with('latestAnalysis')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getSubmittedEssays(): Collection
    {
        return Essay::where('status', 'submitted')
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
    }

    public function getAllEssays(): Collection
    {
        return Essay::with(['user', 'latestAnalysis'])
            ->orderByDesc('created_at')
            ->get();
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    private function simulateScore(int $value, int $minIdeal, int $maxIdeal): int
    {
        if ($value >= $maxIdeal)         return rand(160, 200);
        if ($value >= $minIdeal)         return rand(120, 160);
        if ($value >= $minIdeal * 0.5)   return rand(80, 120);
        return rand(40, 80);
    }

    private function generateHeuristicFeedback(int $words, int $paragraphs, int $c1, int $c2, int $c3, int $c4, int $c5): string
    {
        $total = $c1 + $c2 + $c3 + $c4 + $c5;
        $lines = ["📊 **Análise automática (sem chave de IA configurada)**\n"];

        if ($total >= 800) {
            $lines[] = "Excelente redação! Você demonstra domínio sólido da escrita dissertativa.";
        } elseif ($total >= 600) {
            $lines[] = "Boa redação! Há pontos de melhoria, mas a estrutura geral está adequada.";
        } elseif ($total >= 400) {
            $lines[] = "Redação razoável. Foque em desenvolver melhor seus argumentos e usar conectivos.";
        } else {
            $lines[] = "A redação precisa de melhorias significativas. Pratique mais a estrutura dissertativa.";
        }

        $lines[] = "";
        if ($c1 < 120) $lines[] = "⚠️ **Competência 1 (Norma culta):** Revise a gramática e a ortografia.";
        if ($c2 < 120) $lines[] = "⚠️ **Competência 2 (Tema):** Certifique-se de abordar o tema de forma clara.";
        if ($c3 < 120) $lines[] = "⚠️ **Competência 3 (Argumentação):** Desenvolva argumentos mais consistentes.";
        if ($c4 < 120) $lines[] = "⚠️ **Competência 4 (Coesão):** Use mais conectivos entre os parágrafos.";
        if ($c5 < 120) $lines[] = "⚠️ **Competência 5 (Conclusão):** Elabore uma conclusão mais completa.";

        if ($words < 150) {
            $lines[] = "\n💡 Sua redação tem apenas {$words} palavras. O ideal é entre 300-500 palavras.";
        }
        if ($paragraphs < 4) {
            $lines[] = "💡 Tente organizar em pelo menos 4 parágrafos (introdução, 2 argumentos, conclusão).";
        }

        $lines[] = "\n_Para análise por IA real, configure OPENAI_API_KEY nas configurações._";

        return implode("\n", $lines);
    }
}
