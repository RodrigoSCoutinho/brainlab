@extends('layouts.app')

@section('title', 'Redações dos Alunos')

@section('content')
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-chalkboard-user"></i> Painel do Professor</h1>
        <p class="text-muted">Gerencie e corrija as redações dos seus alunos.</p>
    </div>

    {{-- Pending --}}
    <div class="card">
        <div class="card__header">
            <i class="fa-solid fa-clock"></i> Redações Pendentes ({{ $pendingEssays->count() }})
        </div>

        @if($pendingEssays->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-check-circle"></i></div>
                <div class="empty-state__text">Nenhuma redação pendente para correção.</div>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Título</th>
                        <th>Tema</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingEssays as $essay)
                        <tr>
                            <td>{{ $essay->user->name }}</td>
                            <td><strong>{{ $essay->title }}</strong></td>
                            <td>{{ $essay->subject ?? '—' }}</td>
                            <td>{{ $essay->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('professor.essays.analyze', $essay) }}" class="btn btn--primary" style="padding: 4px 12px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-pen"></i> Corrigir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- All Essays --}}
    <div class="card">
        <div class="card__header">
            <i class="fa-solid fa-list"></i> Todas as Redações ({{ $allEssays->count() }})
        </div>

        @if($allEssays->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-inbox"></i></div>
                <div class="empty-state__text">Nenhuma redação encontrada.</div>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Título</th>
                        <th>Status</th>
                        <th>Nota</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allEssays as $essay)
                        <tr>
                            <td>{{ $essay->user->name }}</td>
                            <td><strong>{{ $essay->title }}</strong></td>
                            <td>
                                @if($essay->status === 'analyzed')
                                    <span class="score-badge score-badge--high">Analisada</span>
                                @else
                                    <span class="score-badge score-badge--mid">Enviada</span>
                                @endif
                            </td>
                            <td>
                                @if($essay->latestAnalysis)
                                    <span class="score-badge score-badge--{{ $essay->latestAnalysis->score >= 600 ? 'high' : ($essay->latestAnalysis->score >= 400 ? 'mid' : 'low') }}">
                                        {{ $essay->latestAnalysis->score }}/1000
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $essay->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('essay.show', $essay) }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
