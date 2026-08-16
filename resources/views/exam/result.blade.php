@extends('layouts.app')

@section('title', 'Resultado do Simulado')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-chart-column"></i> Resultado do Simulado</h1>
        <p>Realizado em {{ $exam->created_at->format('d/m/Y \à\s H:i') }}</p>
    </div>

    {{-- Score --}}
    @php
        $pct = $exam->percentage();
        $badgeClass = $pct >= 70 ? 'high' : ($pct >= 50 ? 'mid' : 'low');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__value">{{ $exam->score }}/{{ $exam->total_questions }}</div>
            <div class="stat-card__label">Acertos</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__value">
                <span class="score-badge score-badge--{{ $badgeClass }}" style="font-size: 1.5rem; padding: 4px 16px;">{{ $pct }}%</span>
            </div>
            <div class="stat-card__label">Pontuação normalizada</div>
        </div>
    </div>

    @if(count($exam->subjectResults()) > 0)
        <div class="card" style="margin-top: 1rem;">
            <div class="card__header">Desempenho por prova</div>
            <div class="subject-grid" style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
                @foreach($exam->subjectResults() as $subjectResult)
                    <div class="stat-card" style="padding: 1rem; text-align: left;">
                        <div class="stat-card__label" style="margin-bottom: 0.5rem;">{{ $subjectResult['subject'] }}</div>
                        <div class="stat-card__value" style="font-size: 1.25rem; margin-bottom: 0.25rem;">
                            {{ $subjectResult['correct'] }}/{{ $subjectResult['questions'] }} acertos
                        </div>
                        <div class="text-muted" style="font-size: 0.95rem;">Normalizado: {{ $subjectResult['percentage'] }}%</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Answers Review --}}
    @foreach($exam->answers as $index => $answer)
        <div class="card">
            <div class="flex-between mb-md">
                <span class="text-muted" style="font-size: 0.85rem;">
                    Questão {{ $index + 1 }} — {{ $answer->question->subject }}
                </span>
                @if($answer->is_correct)
                    <span class="score-badge score-badge--high"><i class="fa-solid fa-check"></i> Correta</span>
                @else
                    <span class="score-badge score-badge--low"><i class="fa-solid fa-xmark"></i> Errada</span>
                @endif
            </div>

            <div class="question__statement">
                {{ $answer->question->statement }}
            </div>

            <ul class="question__options">
                @foreach(['a', 'b', 'c', 'd'] as $letter)
                    @php
                        $classes = 'option';
                        if ($letter === $answer->question->correct_option) $classes .= ' option--correct';
                        elseif ($letter === $answer->selected_option && !$answer->is_correct) $classes .= ' option--wrong';
                    @endphp
                    <div class="{{ $classes }}">
                        <span class="option__letter">{{ strtoupper($letter) }}</span>
                        <span>{{ $answer->question->{'option_' . $letter} }}</span>
                    </div>
                @endforeach
            </ul>

            @if($answer->question->explanation)
                <div style="margin-top: 1rem; padding: 1rem; background: #FFF8E1; border-radius: 8px; border-left: 4px solid #F57F17;">
                    <strong><i class="fa-solid fa-lightbulb"></i> Explicação:</strong><br>
                    {{ $answer->question->explanation }}
                </div>
            @endif
        </div>
    @endforeach

    <div class="flex-between" style="padding: 1rem 0 2rem;">
        <a href="{{ route('dashboard') }}" class="btn btn--secondary btn--lg">← Dashboard</a>
        <a href="{{ route('exam.index') }}" class="btn btn--primary btn--lg">Novo Simulado →</a>
    </div>
</div>
@endsection
