<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class PortugueseLanguageQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'statement' => 'A fala de diferentes comunidades do Brasil, como o uso de “tu” no Rio Grande do Sul e “você” no Sudeste, é um exemplo de qual tipo de variação linguística?',
                'option_a' => 'Variação histórica',
                'option_b' => 'Variação geográfica',
                'option_c' => 'Variação social',
                'option_d' => 'Variação de modalidade',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'O uso distinto de “tu” e “você” em diferentes regiões do país caracteriza variação geográfica.'
            ],
            [
                'statement' => 'Em qual situação o registro oral é mais adequado do que o registro escrito?',
                'option_a' => 'Ao redigir um memorando formal',
                'option_b' => 'Ao publicar uma notícia em jornal',
                'option_c' => 'Ao conversar com amigos em uma roda de conversa',
                'option_d' => 'Ao elaborar um relatório acadêmico',
                'correct_option' => 'c',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A conversa em roda de amigos é um exemplo de situação comunicativa oral informal.'
            ],
            [
                'statement' => 'Qual das palavras abaixo é formada por abreviação?',
                'option_a' => 'porta-voz',
                'option_b' => 'automóvel',
                'option_c' => 'ônibus',
                'option_d' => 'maravilhoso',
                'correct_option' => 'c',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'Ônibus é abreviação do termo “omnibus”.'
            ],
            [
                'statement' => 'No período “Os alunos chegaram cedo e fizeram a tarefa”, a ligação entre as orações é um exemplo de:',
                'option_a' => 'coordenação',
                'option_b' => 'subordinação',
                'option_c' => 'elipse',
                'option_d' => 'anacoluto',
                'correct_option' => 'a',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'As orações são unidas por coordenação, mantendo sentido independente em cada uma.'
            ],
            [
                'statement' => 'Em “A professora explicou a regra à turma”, o termo “a professora” exerce qual função sintática?',
                'option_a' => 'Objeto direto',
                'option_b' => 'Adjunto adverbial',
                'option_c' => 'Sujeito',
                'option_d' => 'Complemento nominal',
                'correct_option' => 'c',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“A professora” é o sujeito da oração, agente da ação de explicar.'
            ],
            [
                'statement' => 'No trecho “O menino estava cansado, então decidiu descansar”, o conectivo “então” estabelece uma relação de:',
                'option_a' => 'comparação',
                'option_b' => 'condição',
                'option_c' => 'conclusão',
                'option_d' => 'finalidade',
                'correct_option' => 'c',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“Então” indica consequência e conclusão em relação à ideia anterior.'
            ],
            [
                'statement' => 'Qual alternativa apresenta grafia correta?',
                'option_a' => 'Irei à pé até a escola.',
                'option_b' => 'Ele trouxe o livro mais caro.',
                'option_c' => 'A criança nao sabia a resposta.',
                'option_d' => 'Ela prefere cafe sem acucar.',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“Mais caro” está correto; as outras opções têm problemas de acentuação ou crase.'
            ],
            [
                'statement' => '“Se você estudar bastante, passará na prova.” Essa frase expressa uma relação de:',
                'option_a' => 'comparação',
                'option_b' => 'condição',
                'option_c' => 'finalidade',
                'option_d' => 'contraste',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A oração introduzida por “se” apresenta uma condição para a ação principal.'
            ],
            [
                'statement' => 'Um texto que relata fatos organizados no tempo e apresenta personagens é característico de qual sequência textual?',
                'option_a' => 'Descritiva',
                'option_b' => 'Narrativa',
                'option_c' => 'Argumentativa',
                'option_d' => 'Expositiva',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A narrativa organiza os fatos em sequência temporal, com personagens e enredo.'
            ],
            [
                'statement' => 'Em qual gênero textual é mais comum o uso público da linguagem para informar a sociedade sobre um fato recente?',
                'option_a' => 'Carta pessoal',
                'option_b' => 'Notícia',
                'option_c' => 'Diálogo informal',
                'option_d' => 'Recado escolar',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A notícia é um gênero de uso público que informa fatos atuais de maneira objetiva.'
            ],
            [
                'statement' => 'No enunciado “A escola deve melhorar o ambiente; por isso, investe em áreas verdes”, o elemento “por isso” é um mecanismo de coesão que indica:',
                'option_a' => 'repetição',
                'option_b' => 'substituição',
                'option_c' => 'causalidade',
                'option_d' => 'sinonímia',
                'correct_option' => 'c',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“Por isso” liga as ideias indicando causa e consequência no texto.'
            ],
            [
                'statement' => 'Qual frase apresenta uma paráfrase correta de “A aluna não entregou o trabalho”?',
                'option_a' => 'A aluna entregou o trabalho atrasado.',
                'option_b' => 'A aluna deixou de entregar o trabalho.',
                'option_c' => 'A aluna recebeu o trabalho.',
                'option_d' => 'A aluna corrigiu o trabalho.',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“Deixar de entregar” mantém o sentido original de não ter entregue.'
            ],
            [
                'statement' => 'Qual alternativa apresenta um período coerente e bem organizado?',
                'option_a' => 'Fui à feira, comprei frutas, o preço estava alto.',
                'option_b' => 'Como estava chovendo, não saí de casa.',
                'option_c' => 'Ele estuda muito, porém ele não consegue entender nada.',
                'option_d' => 'Amanhã, se eu for, talvez não apareça.',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A alternativa B apresenta relações claras e estrutura sintática adequada.'
            ],            [
                'statement' => 'Leia o fragmento: “Ela sempre procura saber o que os outros pensam antes de decidir.” Qual expressão indica opinião alheia?',
                'option_a' => 'Ela sempre procura saber',
                'option_b' => 'o que os outros pensam',
                'option_c' => 'antes de decidir',
                'option_d' => 'sempre procura',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“o que os outros pensam” indica que a ideia se refere à opinião de outras pessoas.'
            ],
            [
                'statement' => 'Qual termo é considerado uma palavra cognata em português e espanhol?',
                'option_a' => 'Casa',
                'option_b' => 'Coelho',
                'option_c' => 'Fogo',
                'option_d' => 'Homem',
                'correct_option' => 'a',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“Casa” é cognata porque possui forma e significado semelhantes em ambas as línguas.'
            ],
            [
                'statement' => 'No trecho “Maria leu o livro que estava sobre a mesa”, a oração “que estava sobre a mesa” é:',
                'option_a' => 'oração coordenada sindética',
                'option_b' => 'oração subordinada adjetiva',
                'option_c' => 'oração subordinada adverbial',
                'option_d' => 'oração reduzida de gerúndio',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => 'A oração descreve o substantivo “livro”, funcionando como adjetiva.'
            ],
            [
                'statement' => 'Qual palavra deve ser acentuada em “O pais estava tranquilo durante a visita”?',
                'option_a' => 'O',
                'option_b' => 'pais',
                'option_c' => 'tranquilo',
                'option_d' => 'visita',
                'correct_option' => 'b',
                'subject' => 'Língua Portuguesa',
                'explanation' => '“País” é uma palavra paroxítona terminada em “s” e precisa de acento agudo para indicar a sílaba tônica.'
            ],        ];

        foreach ($questions as $data) {
            Question::firstOrCreate(
                ['statement' => $data['statement']],
                $data
            );
        }
    }
}
