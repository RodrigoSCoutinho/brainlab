@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1>Olá, {{ Auth::user()->name }}! <i class="fa-solid fa-hand-wave"></i></h1>
            <p class="text-muted">
                Você está conectado como
                <span class="role-badge role-badge--{{ Auth::user()->role }}">{{ Auth::user()->roleLabel() }}</span>
                @if(Auth::user()->isAdmin())
                    &mdash; você tem acesso total à plataforma.
                @elseif(Auth::user()->canTeach())
                    &mdash; você tem acesso ao painel do professor.
                @else
                    &mdash; bons estudos!
                @endif
            </p>
        </div>
        <a href="{{ route('settings.index') }}" class="btn btn--secondary">
            <i class="fa-solid fa-gear"></i> Configurações
        </a>
    </div>

    {{-- Professor / Admin quick panel --}}
    @if(Auth::user()->canTeach())
    <div class="role-panel role-panel--professor">
        <div class="role-panel__header">
            <i class="fa-solid fa-chalkboard-user"></i>
            Painel do Professor
        </div>
        <div class="role-panel__body">
            <a href="{{ route('professor.essays') }}" class="role-panel__action">
                <i class="fa-solid fa-pen-fancy"></i>
                <span>Corrigir Redações</span>
            </a>
            <a href="{{ route('professor.students') }}" class="role-panel__action">
                <i class="fa-solid fa-chart-line"></i>
                <span>Progresso dos Alunos</span>
            </a>
            <a href="{{ route('professor.questions') }}" class="role-panel__action">
                <i class="fa-solid fa-circle-question"></i>
                <span>Banco de Questões</span>
            </a>
            <a href="{{ route('professor.announcements') }}" class="role-panel__action">
                <i class="fa-solid fa-bullhorn"></i>
                <span>Publicar Aviso</span>
            </a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.index') }}" class="role-panel__action role-panel__action--admin">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Painel Admin</span>
                </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="stat-card__value">{{ $stats['total_exams'] }}</div>
            <div class="stat-card__label">Simulados realizados</div>
        </div>
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="stat-card__value">{{ $stats['avg_score'] }}%</div>
            <div class="stat-card__label">Média de acertos</div>
        </div>
        <div class="stat-card stat-card--gold">
            <div class="stat-card__icon"><i class="fa-solid fa-trophy"></i></div>
            <div class="stat-card__value">{{ $stats['best_score'] }}%</div>
            <div class="stat-card__label">Melhor nota</div>
        </div>
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="fa-solid fa-circle-question"></i></div>
            <div class="stat-card__value">{{ $stats['total_questions_answered'] }}</div>
            <div class="stat-card__label">Questões respondidas</div>
        </div>
    </div>

    {{-- Performance by subject --}}
    <div class="card">
        <div class="card__header"><i class="fa-solid fa-chart-simple"></i> Desempenho por Disciplina</div>

        @if(empty($subjectStats))
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-chart-simple"></i></div>
                <div class="empty-state__text">Responda simulados para ver seu desempenho por disciplina.</div>
            </div>
        @else
            <div class="subject-stats">
                @foreach($subjectStats as $subject => $data)
                    @php
                        $barClass = $data['percentage'] >= 70 ? 'high' : ($data['percentage'] >= 50 ? 'mid' : 'low');
                    @endphp
                    <div class="subject-stats__row">
                        <div class="subject-stats__label">
                            <span>{{ $subject }}</span>
                            <span class="text-muted">{{ $data['correct'] }}/{{ $data['total'] }} ({{ $data['percentage'] }}%)</span>
                        </div>
                        <div class="subject-stats__bar">
                            <div class="subject-stats__fill subject-stats__fill--{{ $barClass }}" style="width: {{ $data['percentage'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Announcements (students see these) --}}
    @if($announcements->isNotEmpty())
    <div class="card">
        <div class="card__header"><i class="fa-solid fa-bullhorn"></i> Avisos dos Professores</div>
        <div style="display: grid; gap: 0.75rem;">
            @foreach($announcements as $ann)
                <div class="announcement-item">
                    <div class="announcement-item__title">{{ $ann->title }}</div>
                    <div class="announcement-item__meta text-muted">
                        <i class="fa-solid fa-user-tie"></i> {{ $ann->author->name }}
                        &middot; {{ $ann->created_at->diffForHumans() }}
                    </div>
                    <div class="announcement-item__body">{{ $ann->content }}</div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="card">
        <div class="flex-between">
            <div>
                <div class="card__header">Começar agora</div>
                <p class="text-muted">Pratique ou faça um simulado completo.</p>
            </div>
            <div class="flex gap-md">
                <a href="{{ route('practice.index') }}" class="btn btn--secondary btn--lg"><i class="fa-solid fa-bullseye"></i> Praticar</a>
                <a href="{{ route('exam.index') }}" class="btn btn--primary btn--lg"><i class="fa-solid fa-file-pen"></i> Simulado</a>
            </div>
        </div>
    </div>

    {{-- Exam History --}}
    <div class="card">
        <div class="card__header">Histórico de Simulados</div>

        @if($exams->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-clipboard-list"></i></div>
                <div class="empty-state__text">Nenhum simulado realizado ainda.</div>
                <a href="{{ route('exam.index') }}" class="btn btn--primary">Fazer meu primeiro simulado</a>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Questões</th>
                        <th>Acertos</th>
                        <th>Nota</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                        @php
                            $pct = $exam->percentage();
                            $badgeClass = $pct >= 70 ? 'high' : ($pct >= 50 ? 'mid' : 'low');
                        @endphp
                        <tr>
                            <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $exam->total_questions }}</td>
                            <td>{{ $exam->score }}/{{ $exam->total_questions }}</td>
                            <td><span class="score-badge score-badge--{{ $badgeClass }}">{{ $pct }}%</span></td>
                            <td><a href="{{ route('exam.result', $exam) }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">Ver detalhes</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection

