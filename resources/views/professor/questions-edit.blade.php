@extends('layouts.app')

@section('title', 'Editar Questão')

@section('content')
<div class="container" style="max-width: 800px;">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-pen"></i> Editar Questão #{{ $question->id }}</h1>
            <p class="text-muted">Modifique o enunciado, alternativas ou gabarito.</p>
        </div>
        <a href="{{ route('professor.questions') }}" class="btn btn--secondary">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card">
        <form action="{{ route('professor.questions.update', $question) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="subject">Disciplina</label>
                <input type="text" id="subject" name="subject"
                       value="{{ old('subject', $question->subject) }}"
                       class="form-input @error('subject') is-invalid @enderror"
                       placeholder="ex: Matemática, Português, Ciências...">
                @error('subject')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="statement">Enunciado <span style="color: var(--color-error)">*</span></label>
                <textarea id="statement" name="statement" rows="4"
                          class="form-input @error('statement') is-invalid @enderror">{{ old('statement', $question->statement) }}</textarea>
                @error('statement')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="display: grid; gap: 1rem;">
                @foreach(['A', 'B', 'C', 'D'] as $opt)
                    @php $key = 'option_' . strtolower($opt); @endphp
                    <div class="form-group" style="margin: 0;">
                        <label class="form-label" for="{{ $key }}">Alternativa {{ $opt }} <span style="color: var(--color-error)">*</span></label>
                        <input type="text" id="{{ $key }}" name="{{ $key }}"
                               value="{{ old($key, $question->{$key}) }}"
                               class="form-input @error($key) is-invalid @enderror">
                        @error($key)<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                @endforeach
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Gabarito <span style="color: var(--color-error)">*</span></label>
                <div class="flex gap-md" style="flex-wrap: wrap;">
                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="correct_option" value="{{ strtolower($opt) }}"
                                   {{ strtolower(old('correct_option', $question->correct_option)) === strtolower($opt) ? 'checked' : '' }}>
                            Alternativa {{ $opt }}
                        </label>
                    @endforeach
                </div>
                @error('correct_option')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="explanation">Explicação (opcional)</label>
                <textarea id="explanation" name="explanation" rows="3"
                          class="form-input @error('explanation') is-invalid @enderror">{{ old('explanation', $question->explanation) }}</textarea>
                @error('explanation')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="flex gap-md">
                <button type="submit" class="btn btn--primary">
                    <i class="fa-solid fa-floppy-disk"></i> Atualizar Questão
                </button>
                <a href="{{ route('professor.questions') }}" class="btn btn--secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
