@extends('layouts.app')

@section('title', 'Nova Redação')

@section('content')
<div class="container container--narrow" style="max-width: 820px;">
    <div class="page-header">
        <h1>Nova Redação</h1>
        <p class="text-muted">Escreva sua redação na folha abaixo e envie para análise.</p>
    </div>

    <div class="card">
        <form action="{{ route('essay.store') }}" method="POST" id="essayForm">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="margin-bottom:0;">
                    <label for="title">Título da Redação</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           placeholder="Ex: A importância da educação digital" required>
                    @error('title')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label for="subject">Tema (opcional)</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                           placeholder="Ex: Tecnologia, Meio Ambiente">
                    @error('subject')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Hidden textarea that receives the paper content --}}
            <textarea id="content" name="content" style="display:none;" required>{{ old('content') }}</textarea>
            @error('content')<div class="error mb-sm">{{ $message }}</div>@enderror

            {{-- Paper sheet --}}
            <div class="essay-paper" id="essayPaper">
                <div class="essay-paper__margin"></div>
                <div class="essay-paper__lines" id="paperLines">
                    @for($i = 1; $i <= 30; $i++)
                        <div class="essay-paper__row">
                            <span class="essay-paper__num">{{ $i }}</span>
                            <div class="essay-paper__cell"
                                 contenteditable="true"
                                 data-line="{{ $i }}"
                                 spellcheck="true"></div>
                        </div>
                    @endfor
                </div>
            </div>
            <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.5rem;">
                <i class="fa-solid fa-lightbulb"></i>
                Cada linha comporta aproximadamente 70 caracteres. Pressione <kbd>Enter</kbd> para passar à linha seguinte.
            </p>

            <div class="flex gap-md mt-lg">
                <button type="submit" class="btn btn--primary btn--lg btn--block" id="submitEssay">
                    <i class="fa-solid fa-paper-plane"></i> Enviar Redação
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const lines    = document.querySelectorAll('.essay-paper__cell');
    const hidden   = document.getElementById('content');
    const form     = document.getElementById('essayForm');

    // Restore old content if exists
    const old = hidden.value.trim();
    if (old) {
        const parts = old.split('\n');
        lines.forEach((el, i) => { el.textContent = parts[i] || ''; });
    }

    // Sync each cell to hidden textarea on every keystroke
    function syncContent() {
        hidden.value = Array.from(lines).map(el => el.textContent).join('\n');
    }

    // Key handling: Enter → go to next line, Backspace at line start → go to prev
    lines.forEach((el, idx) => {
        el.addEventListener('input', syncContent);

        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const next = lines[idx + 1];
                if (next) {
                    next.focus();
                    // Move cursor to start
                    const range = document.createRange();
                    const sel   = window.getSelection();
                    range.setStart(next, 0);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
            } else if (e.key === 'Backspace' && el.textContent === '' && idx > 0) {
                e.preventDefault();
                const prev = lines[idx - 1];
                prev.focus();
                // Move cursor to end of previous
                const range = document.createRange();
                const sel   = window.getSelection();
                range.selectNodeContents(prev);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        });
    });

    form.addEventListener('submit', function () {
        syncContent();
    });
})();
</script>
@endsection

