@extends('layouts.app')

@section('title', 'Praticar')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-bullseye"></i> Modo Prática</h1>
            <p>Responda uma questão por vez e receba feedback imediato.</p>
        </div>
        <div class="hearts-row" title="Vidas restantes">
            @for($i = 1; $i <= \App\Services\GamificationService::STARTING_HEARTS; $i++)
                <i class="fa-solid fa-heart {{ $i <= $hearts ? 'hearts-row__heart--full' : 'hearts-row__heart--empty' }}"></i>
            @endfor
        </div>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card__header">Praticar por assunto</div>
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <a href="{{ route('practice.index') }}" class="btn btn--secondary" style="padding: 0.5rem 1rem; font-size: 0.95rem; {{ empty($selectedSubject) ? 'background: var(--ifrn-green); color: var(--text-light);' : '' }}">Todos</a>
            @foreach($subjects as $subject)
                <a href="{{ route('practice.index', ['subject' => $subject]) }}" class="btn btn--secondary" style="padding: 0.5rem 1rem; font-size: 0.95rem; {{ $selectedSubject === $subject ? 'background: var(--ifrn-green); color: var(--text-light);' : '' }}">{{ $subject }}</a>
            @endforeach
        </div>
    </div>

    @if($question)
        <div class="card">
            <span class="text-muted" style="font-size: 0.85rem;">Matéria: <strong>{{ $question->subject }}</strong></span>

            <div class="question__statement">
                {{ $question->statement }}
            </div>

            <form action="{{ route('practice.check') }}" method="POST">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">

                <ul class="question__options">
                    @foreach(['a', 'b', 'c', 'd'] as $letter)
                        <label class="option">
                            <input type="radio" name="selected_option" value="{{ $letter }}" required>
                            <span class="option__letter">{{ strtoupper($letter) }}</span>
                            <span>{{ $question->{'option_' . $letter} }}</span>
                        </label>
                    @endforeach
                </ul>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="btn btn--primary btn--lg">Responder</button>
                </div>
            </form>
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-state__icon">📚</div>
                <div class="empty-state__text">
                    Nenhuma questão disponível{{ $selectedSubject ? ' para ' . $selectedSubject : '' }} no momento.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
