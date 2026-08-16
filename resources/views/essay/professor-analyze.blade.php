@extends('layouts.app')

@section('title', 'Corrigir Redação')

@section('content')
@php
    $lines  = array_pad(explode("\n", $essay->content), 30, '');
    $byLine = $essay->lineComments->groupBy('line_number');
@endphp

<div class="container" style="max-width: 1100px;">
    <div class="page-header">
        <h1>Corrigir Redação</h1>
        <p class="text-muted">
            Aluno: <strong>{{ $essay->user->name }}</strong> &middot;
            {{ $essay->created_at->format('d/m/Y') }}
            @if($essay->subject) &middot; Tema: {{ $essay->subject }} @endif
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 1.5rem; align-items: start;">

        {{-- LEFT: paper sheet --}}
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="card__header">{{ $essay->title }}</div>
            <div class="essay-paper essay-paper--annotate" id="essayPaper">
                <div class="essay-paper__margin"></div>
                <div class="essay-paper__lines">
                    @foreach($lines as $idx => $lineText)
                        @php
                            $lineNum  = $idx + 1;
                            $comments = $byLine->get($lineNum, collect());
                        @endphp
                        <div class="essay-paper__row {{ $comments->isNotEmpty() ? 'essay-paper__row--annotated' : '' }}"
                             data-line="{{ $lineNum }}">
                            <span class="essay-paper__num">{{ $lineNum }}</span>
                            <div class="essay-paper__cell essay-paper__cell--readonly">{{ $lineText }}</div>
                            <button type="button"
                                    class="essay-paper__add-comment"
                                    title="Comentar linha {{ $lineNum }}"
                                    onclick="openCommentPanel({{ $lineNum }})">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                            @foreach($comments as $c)
                                <span class="line-annotation line-annotation--{{ $c->type }}"
                                      title="{{ $c->professor->name }}: {{ $c->comment }}">
                                    <i class="fa-solid fa-{{ $c->type === 'error' ? 'xmark' : ($c->type === 'suggestion' ? 'lightbulb' : 'comment') }}"></i>
                                </span>
                            @endforeach
                        </div>
                        @foreach($comments as $c)
                            <div class="line-comment-block line-comment-block--{{ $c->type }}">
                                <span class="line-comment-block__line">Linha {{ $lineNum }}</span>
                                <span class="line-comment-block__author">{{ $c->professor->name }}</span>
                                <span class="line-comment-block__text">{{ $c->comment }}</span>
                                <form action="{{ route('professor.essays.lines.destroy', [$essay, $c]) }}"
                                      method="POST" style="display:inline; margin-left: auto;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="line-comment-block__delete"
                                            title="Remover comentário">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: sidebar panel --}}
        <div style="display: flex; flex-direction: column; gap: 1rem; position: sticky; top: 80px;">

            {{-- Line comment quick-add panel --}}
            <div class="card" id="linePanel">
                <div class="card__header"><i class="fa-solid fa-pen-to-square"></i> Comentar Linha</div>
                <form action="{{ route('professor.essays.lines.store', $essay) }}" method="POST" id="lineCommentForm">
                    @csrf
                    <div class="form-group">
                        <label>Linha</label>
                        <input type="number" name="line_number" id="lineInput"
                               min="1" max="30" value="1" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label>Tipo</label>
                        <select name="type" class="form-input">
                            <option value="note">📝 Observação</option>
                            <option value="error">❌ Erro</option>
                            <option value="suggestion">💡 Sugestão</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comentário</label>
                        <textarea name="comment" rows="3" class="form-input"
                                  placeholder="Ex: Erro de concordância verbal." required
                                  style="resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn--primary btn--block btn--sm">
                        <i class="fa-solid fa-plus"></i> Adicionar Comentário
                    </button>
                </form>
            </div>

            {{-- Overall analysis form --}}
            <div class="card">
                <div class="card__header"><i class="fa-solid fa-check-double"></i> Nota Final</div>
                <form action="{{ route('professor.essays.analyze.store', $essay) }}" method="POST">
                    @csrf
                    @php
                        $competencies = [
                            'competency_1' => 'Norma culta',
                            'competency_2' => 'Tema',
                            'competency_3' => 'Argumentação',
                            'competency_4' => 'Coesão',
                            'competency_5' => 'Conclusão',
                        ];
                    @endphp
                    @foreach($competencies as $field => $label)
                        <div class="form-group">
                            <label style="font-size:0.85rem;">{{ $label }} <small style="color:#888;">(0-200)</small></label>
                            <input type="number" name="{{ $field }}" min="0" max="200" step="20"
                                   value="{{ old($field, 0) }}" class="form-input" required>
                            @error($field)<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label>Feedback geral</label>
                        <textarea name="feedback" rows="5" class="form-input"
                                  placeholder="Escreva seu feedback para o aluno..." required
                                  style="resize: vertical;">{{ old('feedback') }}</textarea>
                        @error('feedback')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn btn--primary btn--block">
                        <i class="fa-solid fa-check"></i> Enviar Correção
                    </button>
                </form>
            </div>

            <a href="{{ route('professor.essays') }}" class="btn btn--secondary btn--block">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>

<script>
function openCommentPanel(lineNum) {
    document.getElementById('lineInput').value = lineNum;
    document.getElementById('linePanel').scrollIntoView({ behavior: 'smooth', block: 'center' });
    document.querySelector('#linePanel textarea').focus();
}
</script>
@endsection

