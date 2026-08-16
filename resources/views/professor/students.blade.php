@extends('layouts.app')

@section('title', 'Progresso dos Alunos')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-chart-line"></i> Progresso dos Alunos</h1>
            <p class="text-muted">Acompanhe o desempenho de cada estudante da plataforma.</p>
        </div>
        <a href="{{ route('professor.essays') }}" class="btn btn--secondary">
            <i class="fa-solid fa-pen-fancy"></i> Redações
        </a>
    </div>

    @if($students->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="empty-state__text">Nenhum estudante cadastrado ainda.</div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card__header">{{ $students->count() }} estudante(s)</div>
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Aluno</th>
                        <th>Simulados</th>
                        <th>Média</th>
                        <th>Redações</th>
                        <th>Cadastro</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @php
                            $avg = $student->avg_score;
                            $badgeClass = $avg === null ? 'low' : ($avg >= 70 ? 'high' : ($avg >= 50 ? 'mid' : 'low'));
                        @endphp
                        <tr>
                            <td><strong>{{ $student->name }}</strong><br><span class="text-muted" style="font-size:0.8rem">{{ $student->email }}</span></td>
                            <td>{{ $student->exams_count }}</td>
                            <td>
                                @if($avg !== null)
                                    <span class="score-badge score-badge--{{ $badgeClass }}">{{ $avg }}%</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $student->essays_count }}</td>
                            <td class="text-muted">{{ $student->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('professor.students.show', $student) }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-eye"></i> Detalhes
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
