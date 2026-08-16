@extends('layouts.app')

@section('title', 'Perfil do Aluno')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-user-graduate"></i> {{ $student->name }}</h1>
            <p class="text-muted">{{ $student->email }} &middot; Cadastro em {{ $student->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('professor.students') }}" class="btn btn--secondary">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>

    {{-- Summary stats --}}
    <div class="stats-grid">
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon"><i class="fa-solid fa-clipboard-check"></i></div>
            <div class="stat-card__value">{{ $exams->count() }}</div>
            <div class="stat-card__label">Simulados realizados</div>
        </div>
        <div class="stat-card stat-card--green">
            @php $avg = $exams->isNotEmpty() ? round($exams->avg(fn($e) => $e->percentage()), 1) : null; @endphp
            <div class="stat-card__icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="stat-card__value">{{ $avg !== null ? $avg . '%' : '—' }}</div>
            <div class="stat-card__label">Média de acertos</div>
        </div>
        <div class="stat-card stat-card--gold">
            @php $best = $exams->isNotEmpty() ? round($exams->max(fn($e) => $e->percentage()), 1) : null; @endphp
            <div class="stat-card__icon"><i class="fa-solid fa-trophy"></i></div>
            <div class="stat-card__value">{{ $best !== null ? $best . '%' : '—' }}</div>
            <div class="stat-card__label">Melhor nota</div>
        </div>
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon"><i class="fa-solid fa-pen-fancy"></i></div>
            <div class="stat-card__value">{{ $essays->count() }}</div>
            <div class="stat-card__label">Redações enviadas</div>
        </div>
    </div>

    {{-- Exam history --}}
    <div class="card">
        <div class="card__header">Histórico de Simulados</div>
        @if($exams->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-clipboard-list"></i></div>
                <div class="empty-state__text">Nenhum simulado realizado ainda.</div>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr><th>Data</th><th>Questões</th><th>Acertos</th><th>Nota</th></tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                        @php $pct = $exam->percentage(); $bc = $pct >= 70 ? 'high' : ($pct >= 50 ? 'mid' : 'low'); @endphp
                        <tr>
                            <td>{{ $exam->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $exam->total_questions }}</td>
                            <td>{{ $exam->score }}/{{ $exam->total_questions }}</td>
                            <td><span class="score-badge score-badge--{{ $bc }}">{{ $pct }}%</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Essay list --}}
    <div class="card">
        <div class="card__header">Redações</div>
        @if($essays->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-pen-fancy"></i></div>
                <div class="empty-state__text">Nenhuma redação enviada ainda.</div>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr><th>Título</th><th>Tema</th><th>Status</th><th>Data</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach($essays as $essay)
                        <tr>
                            <td><strong>{{ $essay->title }}</strong></td>
                            <td>{{ $essay->subject ?? '—' }}</td>
                            <td>
                                @if($essay->status === 'pending')
                                    <span style="color: var(--color-warning)"><i class="fa-solid fa-clock"></i> Pendente</span>
                                @elseif($essay->status === 'reviewed')
                                    <span style="color: var(--color-success)"><i class="fa-solid fa-check-circle"></i> Corrigida</span>
                                @else
                                    <span class="text-muted">{{ ucfirst($essay->status) }}</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $essay->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('essay.show', $essay) }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-eye"></i> Ver
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
