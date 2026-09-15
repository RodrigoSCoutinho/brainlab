@extends('layouts.app')

@section('title', 'Sessão Encerrada')

@section('content')
<div class="container" style="max-width: 500px; text-align: center;">
    <div class="card" style="padding: 2.5rem 2rem;">
        <div style="font-size: 3rem; color: var(--danger); margin-bottom: 1rem;">
            <i class="fa-solid fa-heart-crack"></i>
        </div>
        <h1 style="margin-bottom: 0.5rem;">Suas vidas acabaram!</h1>
        <p class="text-muted" style="margin-bottom: 2rem;">
            Você errou {{ \App\Services\GamificationService::STARTING_HEARTS }} questões nesta sessão de prática.
            Reveja o conteúdo e tente novamente quando estiver pronto.
        </p>

        <form action="{{ route('practice.restart') }}" method="POST" style="display:inline;">
            @csrf
            @if($selectedSubject)
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
            @endif
            <button type="submit" class="btn btn--primary btn--lg">
                <i class="fa-solid fa-rotate-right"></i> Reiniciar prática
            </button>
        </form>
        <a href="{{ route('gamification.index') }}" class="btn btn--secondary btn--lg" style="margin-top: 0.75rem;">
            <i class="fa-solid fa-trophy"></i> Ver meu progresso
        </a>
    </div>
</div>
@endsection
