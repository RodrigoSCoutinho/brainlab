@extends('layouts.app')

@section('title', 'Iniciar Simulado')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-file-pen"></i> Simulado</h1>
        <p>Faça um simulado com tempo e veja sua pontuação no final.</p>
    </div>

    <div class="card">
        <div class="card__header">Configurar Simulado</div>

        <form action="{{ route('exam.start') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="question_count">Número de questões</label>
                <select id="question_count" name="question_count">
                    <option value="5">5 questões (rápido)</option>
                    <option value="10" selected>10 questões</option>
                    <option value="15">15 questões</option>
                    <option value="20">20 questões (completo)</option>
                </select>
                @error('question_count')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn--primary btn--lg btn--block">🚀 Iniciar Simulado</button>
        </form>
    </div>

    <div class="card">
        <div class="card__header">Avaliação ProITEC</div>
        <p>O processo avaliativo do ProITEC do IFRN consiste em uma avaliação presencial com 50 questões de múltipla escolha:</p>
        <ul style="margin: 0 0 1rem 1.25rem; color: #444;">
            <li><strong>Prova I</strong> – 20 questões de Língua Portuguesa</li>
            <li><strong>Prova II</strong> – 20 questões de Matemática</li>
            <li><strong>Prova III</strong> – 10 questões de Ética e Cidadania</li>
        </ul>
        <p style="margin-bottom: 1rem; color: #757575;">O escore final será disponibilizado pelo número de acertos em cada prova e pontuação normalizada de 0 a 100 pontos.</p>

        <form action="{{ route('exam.proitec') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn--secondary btn--lg btn--block">🎓 Iniciar ProITEC (50 questões)</button>
        </form>
    </div>

    <div class="card" style="text-align: center; color: #757575;">
        <p>⏱ Você terá <strong>2 minutos por questão</strong>.</p>
        <p>Ao finalizar, seu resultado será salvo no histórico.</p>
    </div>
</div>
@endsection
