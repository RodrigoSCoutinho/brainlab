@extends('layouts.app')

@section('title', 'Resultado')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-bullseye"></i> Resultado</h1>
        </div>
        <div class="hearts-row" title="Vidas restantes">
            @for($i = 1; $i <= \App\Services\GamificationService::STARTING_HEARTS; $i++)
                <i class="fa-solid fa-heart {{ $i <= $hearts ? 'hearts-row__heart--full' : 'hearts-row__heart--empty' }}"></i>
            @endfor
        </div>
    </div>

    <div class="feedback {{ $isCorrect ? 'feedback--correct' : 'feedback--wrong' }}">
        @if($isCorrect)
            <i class="fa-solid fa-circle-check"></i> Resposta correta! Muito bem! <span class="xp-gained">+{{ $xpGained }} XP</span>
        @else
            <i class="fa-solid fa-circle-xmark"></i> Resposta incorreta. A correta era: <strong>{{ strtoupper($question->correct_option) }}</strong>
        @endif
    </div>

    <div class="card">
        <span class="text-muted" style="font-size: 0.85rem;">Matéria: <strong>{{ $question->subject }}</strong></span>

        <div class="question__statement">
            {{ $question->statement }}
        </div>

        <ul class="question__options">
            @foreach(['a', 'b', 'c', 'd'] as $letter)
                @php
                    $classes = 'option';
                    if ($letter === $question->correct_option) $classes .= ' option--correct';
                    elseif ($letter === $selected && !$isCorrect) $classes .= ' option--wrong';
                @endphp
                <div class="{{ $classes }}">
                    <span class="option__letter">{{ strtoupper($letter) }}</span>
                    <span>{{ $question->{'option_' . $letter} }}</span>
                </div>
            @endforeach
        </ul>

        @if($question->explanation)
            <div style="margin-top: 1.5rem; padding: 1rem; background: #FFF8E1; border-radius: 8px; border-left: 4px solid #F57F17;">
                <strong><i class="fa-solid fa-lightbulb"></i> Explicação:</strong><br>
                {{ $question->explanation }}
            </div>
        @endif
    </div>

    <a href="{{ route('practice.index', ['subject' => $selectedSubject]) }}" class="btn btn--primary btn--lg">Próxima questão →</a>
</div>
@endsection
