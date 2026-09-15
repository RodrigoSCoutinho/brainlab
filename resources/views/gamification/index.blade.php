@extends('layouts.app')

@section('title', 'Gamificação')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-trophy"></i> Gamificação</h1>
        <p>Acompanhe seu XP, sua sequência de estudos e suas conquistas.</p>
    </div>

    {{-- Top stats: XP + Streak + Daily goal --}}
    <div class="stats-grid">
        <div class="stat-card stat-card--gold">
            <div class="stat-card__icon"><i class="fa-solid fa-star"></i></div>
            <div class="stat-card__value">{{ $xp }}</div>
            <div class="stat-card__label">XP total</div>
        </div>
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="fa-solid fa-fire"></i></div>
            <div class="stat-card__value">{{ $streak['current'] }}</div>
            <div class="stat-card__label">Dias de sequência {{ $streak['practiced_today'] ? '' : '(pratique hoje!)' }}</div>
        </div>
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="fa-solid fa-medal"></i></div>
            <div class="stat-card__value">{{ $streak['longest'] }}</div>
            <div class="stat-card__label">Maior sequência</div>
        </div>
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="fa-solid fa-bullseye"></i></div>
            <div class="stat-card__value">{{ $dailyGoal['answered'] }}/{{ $dailyGoal['goal'] }}</div>
            <div class="stat-card__label">Meta diária</div>
        </div>
    </div>

    {{-- Daily goal progress --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card__header"><i class="fa-solid fa-calendar-check"></i> Meta de Hoje</div>
        <div class="subject-stats__row">
            <div class="subject-stats__label">
                <span>{{ $dailyGoal['completed'] ? 'Meta concluída! 🎉' : 'Responda ' . $dailyGoal['goal'] . ' questões hoje' }}</span>
                <span class="text-muted">{{ $dailyGoal['answered'] }}/{{ $dailyGoal['goal'] }}</span>
            </div>
            <div class="subject-stats__bar">
                <div class="subject-stats__fill subject-stats__fill--{{ $dailyGoal['completed'] ? 'high' : 'mid' }}" style="width: {{ $dailyGoal['percentage'] }}%;"></div>
            </div>
        </div>
        @if(!$dailyGoal['completed'])
            <a href="{{ route('practice.index') }}" class="btn btn--primary" style="margin-top: 1rem;">
                <i class="fa-solid fa-bullseye"></i> Praticar agora
            </a>
        @endif
    </div>

    {{-- Level per subject --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card__header"><i class="fa-solid fa-layer-group"></i> Nível por Disciplina</div>

        @if(empty($subjectLevels))
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-layer-group"></i></div>
                <div class="empty-state__text">Pratique questões para começar a ganhar XP e subir de nível.</div>
            </div>
        @else
            <div class="subject-stats">
                @foreach($subjectLevels as $subject => $data)
                    <div class="subject-stats__row">
                        <div class="subject-stats__label">
                            <span>{{ $subject }} &middot; <strong>Nível {{ $data['level'] }}</strong></span>
                            <span class="text-muted">{{ $data['xp_into_level'] }}/{{ $data['xp_for_next_level'] }} XP</span>
                        </div>
                        <div class="subject-stats__bar">
                            <div class="subject-stats__fill subject-stats__fill--high" style="width: {{ $data['xp_into_level'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Achievements --}}
    <div class="card">
        <div class="card__header"><i class="fa-solid fa-award"></i> Conquistas</div>
        <div class="achievements-grid">
            @foreach($achievements as $achievement)
                <div class="achievement-badge {{ $achievement['unlocked'] ? 'achievement-badge--unlocked' : 'achievement-badge--locked' }}">
                    <div class="achievement-badge__icon"><i class="fa-solid {{ $achievement['icon'] }}"></i></div>
                    <div class="achievement-badge__name">{{ $achievement['name'] }}</div>
                    <div class="achievement-badge__desc">{{ $achievement['description'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
