@extends('layouts.app')

@section('title', 'Avisos aos Alunos')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-bullhorn"></i> Avisos</h1>
            <p class="text-muted">Publique comunicados visíveis para todos os estudantes.</p>
        </div>
    </div>

    {{-- New announcement form --}}
    <div class="card">
        <div class="card__header">Novo Aviso</div>
        <form action="{{ route('professor.announcements.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="ann-title">Título <span style="color: var(--color-error)">*</span></label>
                <input type="text" id="ann-title" name="title" value="{{ old('title') }}"
                       class="form-input @error('title') is-invalid @enderror"
                       placeholder="Título do aviso...">
                @error('title')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="ann-content">Conteúdo <span style="color: var(--color-error)">*</span></label>
                <textarea id="ann-content" name="content" rows="4"
                          class="form-input @error('content') is-invalid @enderror"
                          placeholder="Escreva o conteúdo do aviso...">{{ old('content') }}</textarea>
                @error('content')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn--primary">
                <i class="fa-solid fa-paper-plane"></i> Publicar Aviso
            </button>
        </form>
    </div>

    {{-- Published announcements --}}
    <div class="card">
        <div class="card__header">Avisos publicados ({{ $announcements->count() }})</div>

        @if($announcements->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-bullhorn"></i></div>
                <div class="empty-state__text">Nenhum aviso publicado ainda.</div>
            </div>
        @else
            <div style="display: grid; gap: 1rem;">
                @foreach($announcements as $ann)
                    <div style="border: 1px solid var(--border); border-radius: var(--radius); padding: 1.25rem;">
                        <div class="flex-between" style="margin-bottom: 0.5rem;">
                            <strong>{{ $ann->title }}</strong>
                            <div class="flex gap-sm" style="align-items: center;">
                                <span class="text-muted" style="font-size: 0.8rem;">
                                    {{ $ann->author->name }} &middot; {{ $ann->created_at->diffForHumans() }}
                                </span>
                                <form action="{{ route('professor.announcements.destroy', $ann) }}" method="POST"
                                      onsubmit="return confirm('Remover este aviso?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn--danger" style="padding: 2px 10px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p style="margin: 0; white-space: pre-line;">{{ $ann->content }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
