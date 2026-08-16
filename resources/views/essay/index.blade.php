@extends('layouts.app')

@section('title', 'Minhas Redações')

@section('content')
<div class="container">
    <div class="page-header flex-between">
        <div>
            <h1>Minhas Redações</h1>
            <p class="text-muted">Envie redações e receba análises por IA ou pelo professor.</p>
        </div>
        <a href="{{ route('essay.create') }}" class="btn btn--primary btn--lg">
            <i class="fa-solid fa-plus"></i> Nova Redação
        </a>
    </div>

    @if($essays->isEmpty())
        <div class="card">
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-pen-fancy"></i></div>
                <div class="empty-state__text">Nenhuma redação enviada ainda.</div>
                <a href="{{ route('essay.create') }}" class="btn btn--primary">Escrever minha primeira redação</a>
            </div>
        </div>
    @else
        <div class="card">
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tema</th>
                        <th>Status</th>
                        <th>Nota</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($essays as $essay)
                        <tr>
                            <td><strong>{{ $essay->title }}</strong></td>
                            <td>{{ $essay->subject ?? '—' }}</td>
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
        </div>
    @endif
</div>
@endsection
