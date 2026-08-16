@extends('layouts.app')

@section('title', 'Nova Questão')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-plus"></i> Nova Questão</h1>
            <p class="text-muted">Adicione uma questão ao banco de questões da plataforma.</p>
        </div>
        <a href="{{ route('professor.questions') }}" class="btn btn--secondary">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card">
        <form action="{{ route('professor.questions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="subject">Disciplina</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                       class="form-input @error('subject') is-invalid @enderror"
                       placeholder="ex: Matemática, Português, Ciências...">
                @error('subject')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="statement">Enunciado <span style="color: var(--color-error)">*</span></label>
                <textarea id="statement" name="statement" rows="4"
                          class="form-input @error('statement') is-invalid @enderror"
                          placeholder="Digite o enunciado completo da questão...">{{ old('statement') }}</textarea>
                @error('statement')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="display: grid; gap: 1rem;">
                @foreach(['A', 'B', 'C', 'D'] as $opt)
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" for="option_{{ strtolower($opt) }}">Alternativa {{ $opt }} <span style="color: var(--color-error)">*</span></label>
                        <input type="text" id="option_{{ strtolower($opt) }}" name="option_{{ strtolower($opt) }}"
                               value="{{ old('option_' . strtolower($opt)) }}"
                               class="form-input @error('option_' . strtolower($opt)) is-invalid @enderror"
                               placeholder="Texto da alternativa {{ $opt }}">
                        @error('option_' . strtolower($opt))<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                @endforeach
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Gabarito <span style="color: var(--color-error)">*</span></label>
                <div class="flex gap-md" style="flex-wrap: wrap;">
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="correct_option" value="{{ $opt }}"
                                   {{ old('correct_option') === $opt ? 'checked' : '' }}>
                            Alternativa {{ $opt }}
                        </label>
                    @endforeach
                </div>
                @error('correct_option')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="explanation">Explicação (opcional)</label>
                <textarea id="explanation" name="explanation" rows="3"
                          class="form-input @error('explanation') is-invalid @enderror"
                          placeholder="Explique o raciocínio da resposta correta...">{{ old('explanation') }}</textarea>
                @error('explanation')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="flex gap-md">
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar Questão
                </button>
                <a href="{{ route('professor.questions') }}" class="btn btn--secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
