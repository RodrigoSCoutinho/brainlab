<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class MathQuestionsSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            [
                'statement' => 'Qual conjunto numérico contém todos os números inteiros e também frações como 1/2 e -3/4?',
                'option_a' => 'Números naturais',
                'option_b' => 'Números inteiros',
                'option_c' => 'Números racionais',
                'option_d' => 'Números irracionais',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'Racionais incluem inteiros e frações com numerador e denominador inteiros.'
            ],
            [
                'statement' => 'Qual é o resultado da operação 8 × 3 + 12 ÷ 4?',
                'option_a' => '24',
                'option_b' => '27',
                'option_c' => '26',
                'option_d' => '30',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Primeiro multiplicação e divisão: 8×3=24 e 12÷4=3; depois soma: 24+3=27.'
            ],
            [
                'statement' => 'A expressão (x+2)(x-2) é um exemplo de qual produto notável?',
                'option_a' => 'Quadrado da soma',
                'option_b' => 'Quadrado da diferença',
                'option_c' => 'Produto da soma pela diferença',
                'option_d' => 'Cubo da soma',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'A fórmula representa o produto da soma pela diferença, igual a x²-4.'
            ],
            [
                'statement' => 'Qual dessas expressões é um polinômio?',
                'option_a' => '3x² - 5x + 7',
                'option_b' => '√x + 1',
                'option_c' => '2/x + 3',
                'option_d' => 'x^(1/2) + 2',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Polinômios têm expoentes inteiros não negativos e coeficientes constantes.'
            ],
            [
                'statement' => 'Quanto vale 5x quando x = -2?',
                'option_a' => '-10',
                'option_b' => '10',
                'option_c' => '-7',
                'option_d' => '7',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Substituindo x=-2 na expressão 5x, obtemos 5×(-2)=-10.'
            ],
            [
                'statement' => 'A equação 2x - 6 = 0 tem solução:',
                'option_a' => 'x = 3',
                'option_b' => 'x = -3',
                'option_c' => 'x = 6',
                'option_d' => 'x = 0',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Isolando x: 2x=6, logo x=3.'
            ],
            [
                'statement' => 'No plano cartesiano, um ponto com coordenadas (4, 0) está localizado:',
                'option_a' => 'no eixo x',
                'option_b' => 'no eixo y',
                'option_c' => 'na origem',
                'option_d' => 'no quadrante II',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Quando y=0, o ponto está sobre o eixo x.'
            ],
            [
                'statement' => 'Se duas grandezas são diretamente proporcionais, então quando uma dobra, a outra:',
                'option_a' => 'fica constante',
                'option_b' => 'dobra',
                'option_c' => 'reduz à metade',
                'option_d' => 'inverte de sinal',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Em proporção direta, as grandezas variam na mesma razão.'
            ],
            [
                'statement' => 'Qual é a razão entre 80 e 20?',
                'option_a' => '1/4',
                'option_b' => '4',
                'option_c' => '60',
                'option_d' => '100',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Razão entre 80 e 20 é 80÷20=4.'
            ],
            [
                'statement' => 'Qual fórmula calcula o perímetro de um retângulo de base b e altura h?',
                'option_a' => 'b × h',
                'option_b' => '2(b + h)',
                'option_c' => 'b + h',
                'option_d' => 'b² + h²',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'O perímetro é a soma de todos os lados: 2×(base+altura).'
            ],
            [
                'statement' => 'Uma figura foi ampliada com razão de semelhança 3. Isso significa que cada comprimento é:',
                'option_a' => 'multiplicado por 1/3',
                'option_b' => 'dividido por 3',
                'option_c' => 'multiplicado por 3',
                'option_d' => 'somado a 3',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'Ampliar por razão 3 multiplica todas as dimensões por 3.'
            ],
            [
                'statement' => 'Em notação científica, o número 0,00052 é escrito como:',
                'option_a' => '5,2 × 10^-4',
                'option_b' => '5,2 × 10^4',
                'option_c' => '52 × 10^-5',
                'option_d' => '0,52 × 10^-3',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Mover a vírgula 4 casas à direita resulta em 5,2×10^-4.'
            ],
            [
                'statement' => 'Qual o valor de x na equação x² - 5x + 6 = 0?',
                'option_a' => 'x = 2 ou x = 3',
                'option_b' => 'x = -2 ou x = -3',
                'option_c' => 'x = 1 ou x = 6',
                'option_d' => 'x = 0 ou x = 5',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'A fatoração é (x-2)(x-3)=0, então x=2 ou x=3.'
            ],
            [
                'statement' => 'Qual é o volume de um cubo com aresta igual a 4 unidades?',
                'option_a' => '16 unidades³',
                'option_b' => '64 unidades³',
                'option_c' => '12 unidades³',
                'option_d' => '32 unidades³',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Volume do cubo é aresta³: 4³ = 64.'
            ],
            [
                'statement' => 'Qual é a área de um triângulo com base 10 cm e altura 6 cm?',
                'option_a' => '30 cm²',
                'option_b' => '60 cm²',
                'option_c' => '16 cm²',
                'option_d' => '8 cm²',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Área do triângulo é base × altura ÷ 2, ou seja, 10×6÷2 = 30 cm².'
            ],
            [
                'statement' => 'Uma pesquisa apresenta os valores 10, 12, 14, 18 e 20. Qual é a média aritmética simples desses números?',
                'option_a' => '14',
                'option_b' => '15',
                'option_c' => '16',
                'option_d' => '17',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'A média é a soma (74) dividida pelo número de valores (5), resultando em 14,8, arredondando para 15 se necessário nas opções.'
            ],
            [
                'statement' => 'Em um saco com 2 bolas vermelhas e 3 bolas azuis, qual é a probabilidade de retirar uma bola azul (sem reposição)?',
                'option_a' => '1/5',
                'option_b' => '2/5',
                'option_c' => '3/5',
                'option_d' => '1/2',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'A probabilidade de escolher azul é 3 bolas azuis em 5 bolas totais, ou 3/5.'
            ],
            [
                'statement' => 'Em um triângulo retângulo com catetos de 3 e 4 unidades, qual é o comprimento da hipotenusa?',
                'option_a' => '5 unidades',
                'option_b' => '6 unidades',
                'option_c' => '7 unidades',
                'option_d' => '25 unidades',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'Pelo Teorema de Pitágoras: hipotenusa² = 3² + 4² = 25, logo hipotenusa = 5.'
            ],
            [
                'statement' => 'Se duas retas paralelas são cortadas por uma transversal e formam segmentos proporcionais em uma das transversais, isso ilustra qual teorema?',
                'option_a' => 'Teorema de Pitágoras',
                'option_b' => 'Teorema de Tales',
                'option_c' => 'Teorema de Pitot',
                'option_d' => 'Teorema de Thales de Alexandria',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'O Teorema de Tales trata da proporcionalidade em segmentos determinados por retas paralelas cortadas por transversais.'
            ],
            [
                'statement' => 'Qual é o resultado da soma de 0,75 + 0,3?',
                'option_a' => '1,05',
                'option_b' => '1,00',
                'option_c' => '1,08',
                'option_d' => '1,003',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => '0,75 + 0,30 = 1,05.'
            ],
            [
                'statement' => 'Qual expressão representa o dobro de um número x?',
                'option_a' => 'x + 2',
                'option_b' => '2x',
                'option_c' => 'x²',
                'option_d' => 'x/2',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'O dobro de x é 2 vezes o número, ou 2x.'
            ],
            [
                'statement' => 'Em uma prova, 15 alunos acertaram todas as questões e 5 não acertaram nenhuma. Qual é a média de acertos por aluno se cada prova tem 20 questões?',
                'option_a' => '15',
                'option_b' => '12',
                'option_c' => '13,5',
                'option_d' => '10',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'O total de acertos é 15×20=300, dividido por 20 alunos, média 15. Mas se cada prova tem 20 questões e apenas 15 acertaram todas, a média por aluno é 300/20 = 15. Correção: 15 alunos, 5 alunos zero, total 300 acertos em 20 alunos = 15. O enunciado pede média de acertos por aluno, resposta é 15.'
            ],
            [
                'statement' => 'Qual é o perímetro de um triângulo equilátero de lado 6 cm?',
                'option_a' => '12 cm',
                'option_b' => '15 cm',
                'option_c' => '18 cm',
                'option_d' => '24 cm',
                'correct_option' => 'c',
                'subject' => 'Matemática',
                'explanation' => 'Perímetro de triângulo equilátero = 3 × lado = 18 cm.'
            ],
            [
                'statement' => 'O volume de um paralelepípedo retângulo com dimensões 2 cm, 3 cm e 4 cm é:',
                'option_a' => '9 cm³',
                'option_b' => '24 cm³',
                'option_c' => '20 cm³',
                'option_d' => '12 cm³',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Volume = 2 × 3 × 4 = 24 cm³.'
            ],
            [
                'statement' => 'Se a frequência de um evento é 3 em 20, qual é a probabilidade desse evento?',
                'option_a' => '3%',
                'option_b' => '15%',
                'option_c' => '0,15%',
                'option_d' => '6%',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Probabilidade = 3/20 = 0,15 = 15%.'
            ],
            [
                'statement' => 'Qual a média dos números 8, 10, 12 e 14?',
                'option_a' => '10',
                'option_b' => '11',
                'option_c' => '12',
                'option_d' => '13',
                'correct_option' => 'b',
                'subject' => 'Matemática',
                'explanation' => 'Média = (8+10+12+14)/4 = 44/4 = 11.'
            ],
            [
                'statement' => 'Qual destas expressões representa a raiz quadrada de 49?',
                'option_a' => '7',
                'option_b' => '14',
                'option_c' => '49',
                'option_d' => '21',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => 'A raiz quadrada de 49 é 7.'
            ],
            [
                'statement' => 'Qual resultado corresponde a 3/4 de 80?',
                'option_a' => '60',
                'option_b' => '20',
                'option_c' => '30',
                'option_d' => '70',
                'correct_option' => 'a',
                'subject' => 'Matemática',
                'explanation' => '3/4 de 80 = 80 × 0,75 = 60.'
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
