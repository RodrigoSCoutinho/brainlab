<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class EthicsCitizenshipQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'statement' => 'O que significa agir com ética no dia a dia da escola?',
                'option_a' => 'Sempre conseguir notas altas, mesmo que seja com cola.',
                'option_b' => 'Compartilhar materiais apenas com os amigos mais próximos.',
                'option_c' => 'Respeitar regras, ser honesto e tratar os colegas com justiça.',
                'option_d' => 'Evitar frequência em atividades para não ser responsabilizado.',
                'correct_option' => 'C',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Agir com ética envolve honestidade, respeito e justiça nas relações.'
            ],
            [
                'statement' => 'Qual atitude demonstra cidadania em uma comunidade escolar?',
                'option_a' => 'Desprezar opiniões diferentes para impor a própria ideia.',
                'option_b' => 'Cuidar do ambiente, participar de decisões e respeitar os outros.',
                'option_c' => 'Usar o pátio da escola como lugar para descartar lixo no chão.',
                'option_d' => 'Pedir a amigos que façam seu trabalho escolar.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Cidadania envolve cuidado com o ambiente e participação consciente na comunidade.'
            ],
            [
                'statement' => 'Por que é importante respeitar pessoas de diferentes etnias e culturas?',
                'option_a' => 'Porque isso garante que só sua cultura seja valorizada.',
                'option_b' => 'Porque a diversidade torna a sociedade mais rica e justa.',
                'option_c' => 'Porque os outros precisam seguir as mesmas regras que você.',
                'option_d' => 'Porque assim ninguém poderá expressar suas ideias.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'A diversidade cultural e étnica enriquece a sociedade e merece respeito.'
            ],
            [
                'statement' => 'Qual é uma forma de trabalhar de maneira ética no futuro profissional?',
                'option_a' => 'Aceitar qualquer tarefa mesmo sabendo que é vítima de exploração.',
                'option_b' => 'Cumprir horários, ser responsável e preservar o ambiente de trabalho.',
                'option_c' => 'Priorizar apenas o lucro, mesmo que prejudique colegas.',
                'option_d' => 'Ocultar problemas para parecer mais eficiente.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Ética no trabalho inclui responsabilidade, respeito e cuidado com o ambiente.'
            ],
            [
                'statement' => 'Qual direito é um exemplo de direito humano básico?',
                'option_a' => 'O direito de possuir sempre o maior brinquedo da turma.',
                'option_b' => 'O direito de acesso à educação e ao tratamento igualitário.',
                'option_c' => 'O direito de não fazer tarefas escolares.',
                'option_d' => 'O direito de escolher o voto à vontade aos 12 anos.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Direitos humanos incluem acesso à educação e tratamento igualitário a todos.'
            ],
            [
                'statement' => 'Como a política pode ajudar a construir uma sociedade mais justa?',
                'option_a' => 'Ignorando problemas e deixando tudo como está.',
                'option_b' => 'Tomando decisões em conjunto para garantir direitos e igualdade.',
                'option_c' => 'Reforçando apenas as ideias de quem tem mais dinheiro.',
                'option_d' => 'Fazendo com que as pessoas só obedeçam sem questionar.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'A política deve promover decisões coletivas que valorizem os direitos de todos.'
            ],
            [
                'statement' => 'Por que é importante preservar o meio ambiente para a sobrevivência humana?',
                'option_a' => 'Porque o meio ambiente não interfere na nossa vida diária.',
                'option_b' => 'Porque ações de poluição e desperdício podem prejudicar a vida no planeta.',
                'option_c' => 'Porque só os governos devem cuidar da natureza, não as pessoas.',
                'option_d' => 'Porque o ambiente é infinito e nunca pode se esgotar.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Preservar o meio ambiente é essencial para manter condições de vida saudáveis.'
            ],
            [
                'statement' => 'O que é desenvolvimento sustentável?',
                'option_a' => 'Usar todos os recursos naturais sem se preocupar com o futuro.',
                'option_b' => 'Produzir e consumir de forma que garanta qualidade de vida hoje e no futuro.',
                'option_c' => 'Aumentar o consumo para crescer economicamente, mesmo prejudicando a natureza.',
                'option_d' => 'Priorizar apenas o lucro das empresas e não o bem-estar das pessoas.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Desenvolvimento sustentável equilibra necessidades atuais e preservação futura.'
            ],
            [
                'statement' => 'Qual atitude contribui para a prevenção e manutenção da saúde na escola?',
                'option_a' => 'Compartilhar objetos íntimos, como escovas de dente.',
                'option_b' => 'Lavar as mãos antes das refeições e cuidar da higiene pessoal.',
                'option_c' => 'Ficar em casa apenas quando tiver muita vontade.',
                'option_d' => 'Consumir alimentos sem verificar se estão em bom estado.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Boa higiene e prevenção são fundamentais para a saúde coletiva.'
            ],
            [
                'statement' => 'Qual exemplo mostra respeito à diversidade cultural em uma festa de escola?',
                'option_a' => 'Reforçar que apenas um tipo de música é boa.',
                'option_b' => 'Zombar de comidas diferentes só por elas serem desconhecidas.',
                'option_c' => 'Apreciar e aprender sobre comidas, músicas e tradições de diferentes culturas.',
                'option_d' => 'Impor a sua própria cultura como melhor para todos.',
                'correct_option' => 'C',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Respeitar diversidade cultural significa aceitar e valorizar diferenças.'
            ],
            [
                'statement' => 'O que é justo em um ambiente escolar?',
                'option_a' => 'Dar tratamento diferente a alunos apenas porque são amigos.',
                'option_b' => 'Distribuir responsabilidades e chances de forma equilibrada.',
                'option_c' => 'Permitir que alguns alunos façam as tarefas para os colegas.',
                'option_d' => 'Fazer regras apenas para quem chega atrasado.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Justiça envolve tratar as pessoas de modo equilibrado e responsável.'
            ],
            [
                'statement' => 'Qual ação demonstra responsabilidade social?',
                'option_a' => 'Largar lixo na rua porque ninguém está olhando.',
                'option_b' => 'Ajudar a limpar um parque usado pela comunidade.',
                'option_c' => 'Ignorar regras de trânsito ao dirigir rápido.',
                'option_d' => 'Pedir a outra pessoa para fazer sua parte no grupo.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Responsabilidade social é agir para beneficiar a comunidade e o meio ambiente.'
            ],
            [
                'statement' => 'O que caracteriza um cidadão ativo? ',
                'option_a' => 'Participar apenas quando algo afeta diretamente sua família.',
                'option_b' => 'Cobrar melhorias e também colaborar com a comunidade.',
                'option_c' => 'Não se envolver em discussões públicas.',
                'option_d' => 'Somente respeitar regras se for conveniente.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Um cidadão ativo participa e contribui para a melhoria coletiva.'
            ],
            [
                'statement' => 'Por que é importante denunciar atos de injustiça ou discriminação?',
                'option_a' => 'Para punir as pessoas sem buscar solução.',
                'option_b' => 'Para combater comportamentos que afetam negativamente outras pessoas.',
                'option_c' => 'Porque isso cria inimizades na escola.',
                'option_d' => 'Porque não é responsabilidade de ninguém agir.',
                'correct_option' => 'B',
                'subject' => 'Ética e Cidadania',
                'explanation' => 'Denunciar injustiça ajuda a proteger direitos e promover um ambiente mais seguro.'
            ],
        ];

        foreach ($questions as $data) {
            Question::firstOrCreate(
                ['statement' => $data['statement']],
                $data
            );
        }
    }
}
