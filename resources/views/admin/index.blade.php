@extends('layouts.app')

@section('title', 'Painel Administrativo')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-shield-halved"></i> Painel Administrativo</h1>
        <p class="text-muted">Visão geral da plataforma e ferramentas de gerenciamento.</p>
    </div>

    {{-- Platform Stats --}}
    <div class="stats-grid">
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="fa-solid fa-users"></i></div>
            <div class="stat-card__value">{{ $stats['total_users'] }}</div>
            <div class="stat-card__label">Usuários totais</div>
        </div>
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="fa-solid fa-user-graduate"></i></div>
            <div class="stat-card__value">{{ $stats['students'] }}</div>
            <div class="stat-card__label">Estudantes</div>
        </div>
        <div class="stat-card stat-card--gold">
            <div class="stat-card__icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div class="stat-card__value">{{ $stats['professors'] }}</div>
            <div class="stat-card__label">Professores</div>
        </div>
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="stat-card__value">{{ $stats['total_exams'] }}</div>
            <div class="stat-card__label">Simulados realizados</div>
        </div>
        <div class="stat-card stat-card--red">
            <div class="stat-card__icon"><i class="fa-solid fa-pen-fancy"></i></div>
            <div class="stat-card__value">{{ $stats['total_essays'] }}</div>
            <div class="stat-card__label">Redações enviadas</div>
        </div>
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon"><i class="fa-solid fa-circle-question"></i></div>
            <div class="stat-card__value">{{ $stats['total_questions'] }}</div>
            <div class="stat-card__label">Questões no banco</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card">
        <div class="card__header">Ações rápidas</div>
        <div class="flex gap-md" style="flex-wrap: wrap;">
            <a href="{{ route('admin.users') }}" class="btn btn--primary">
                <i class="fa-solid fa-users-gear"></i> Gerenciar Usuários
            </a>
            <a href="{{ route('professor.questions') }}" class="btn btn--secondary">
                <i class="fa-solid fa-circle-question"></i> Banco de Questões
            </a>
            <a href="{{ route('professor.essays') }}" class="btn btn--secondary">
                <i class="fa-solid fa-pen-fancy"></i> Redações
            </a>
            <a href="{{ route('professor.students') }}" class="btn btn--secondary">
                <i class="fa-solid fa-chart-line"></i> Progresso dos Alunos
            </a>
            <a href="{{ route('professor.announcements') }}" class="btn btn--secondary">
                <i class="fa-solid fa-bullhorn"></i> Avisos
            </a>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="card">
        <div class="card__header">Usuários recentes</div>
        <table class="exam-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Criado em</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td class="text-muted">{{ $u->email }}</td>
                        <td><span class="role-badge role-badge--{{ $u->role }}">{{ $u->roleLabel() }}</span></td>
                        <td class="text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top: 1rem;">
            <a href="{{ route('admin.users') }}" class="btn btn--secondary">Ver todos os usuários</a>
        </div>
    </div>
</div>
@endsection
