@extends('layouts.app')

@section('title', 'Banco de Questões')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-circle-question"></i> Banco de Questões</h1>
            <p class="text-muted">Gerencie as questões usadas nos simulados e práticas.</p>
        </div>
        <a href="{{ route('professor.questions.create') }}" class="btn btn--primary">
            <i class="fa-solid fa-plus"></i> Nova Questão
        </a>
    </div>

    <div class="card">
        <div class="card__header">{{ $questions->total() }} questão(ões)</div>

        @if($questions->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-circle-question"></i></div>
                <div class="empty-state__text">Nenhuma questão cadastrada ainda.</div>
                <a href="{{ route('professor.questions.create') }}" class="btn btn--primary">Adicionar primeira questão</a>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Enunciado</th>
                        <th>Disciplina</th>
                        <th>Gabarito</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($questions as $question)
                        <tr>
                            <td class="text-muted">{{ $question->id }}</td>
                            <td style="max-width: 400px;">{{ \Illuminate\Support\Str::limit($question->statement, 80) }}</td>
                            <td>
                                @if($question->subject)
                                    {{ $question->subject }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><strong>{{ $question->correct_option }}</strong></td>
                            <td>
                                <div class="flex gap-sm">
                                    <a href="{{ route('professor.questions.edit', $question) }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('professor.questions.destroy', $question) }}" method="POST"
                                          onsubmit="return confirm('Remover esta questão?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--danger" style="padding: 4px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $questions->links('pagination::simple-default') }}</div>
        @endif
    </div>
</div>
@endsection
