@extends('layouts.app')

@section('title', $essay->title)

@section('content')
@php
    $lines = array_pad(explode("\n", $essay->content), 30, '');
    $byLine = $essay->lineComments->groupBy('line_number');
@endphp

<div class="container" style="max-width: 860px;">
    <div class="page-header">
        <h1>{{ $essay->title }}</h1>
        <p class="text-muted">
            Por {{ $essay->user->name }} &middot; {{ $essay->created_at->format('d/m/Y H:i') }}
            @if($essay->subject) &middot; Tema: {{ $essay->subject }} @endif
        </p>
    </div>

    {{-- Paper sheet --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="card__header" style="display:flex; justify-content:space-between; align-items:center;">
            <span><i class="fa-solid fa-file-lines"></i> Redação</span>
            @if($essay->user_id === Auth::id() && $essay->analyses->where('analysis_type', 'ai')->isEmpty())
                <form action="{{ route('essay.analyze-ai', $essay) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn btn--primary btn--sm">
                        <i class="fa-solid fa-robot"></i> Analisar com IA
                    </button>
                </form>
            @endif
        </div>

        <div class="essay-paper essay-paper--readonly">
            <div class="essay-paper__margin"></div>
            <div class="essay-paper__lines">
                @foreach($lines as $idx => $lineText)
                    @php
                        $lineNum  = $idx + 1;
                        $comments = $byLine->get($lineNum, collect());
                        $hasComment = $comments->isNotEmpty();
                    @endphp
                    <div class="essay-paper__row {{ $hasComment ? 'essay-paper__row--annotated' : '' }}">
                        <span class="essay-paper__num">{{ $lineNum }}</span>
                        <div class="essay-paper__cell essay-paper__cell--readonly">{{ $lineText }}</div>
                        @if($hasComment)
                            <div class="essay-paper__annotations">
                                @foreach($comments as $c)
                                    <span class="line-annotation line-annotation--{{ $c->type }}"
                                          title="{{ $c->professor->name ?? 'Professor' }}: {{ $c->comment }}">
                                        <i class="fa-solid fa-{{ $c->type === 'error' ? 'xmark' : ($c->type === 'suggestion' ? 'lightbulb' : 'comment') }}"></i>
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @if($hasComment)
                        @foreach($comments as $c)
                            <div class="line-comment-block line-comment-block--{{ $c->type }}">
                                <span class="line-comment-block__line">Linha {{ $lineNum }}</span>
                                <span class="line-comment-block__author">{{ $c->professor->name ?? 'Professor' }}</span>
                                <span class="line-comment-block__text">{{ $c->comment }}</span>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Analyses --}}
    @foreach($essay->analyses as $analysis)
        <div class="card mt-lg" style="border-left: 4px solid {{ $analysis->analysis_type === 'ai' ? '#2196F3' : '#2E7D32' }};">
            <div class="card__header flex-between">
                <span>
                    @if($analysis->analysis_type === 'ai')
                        <i class="fa-solid fa-robot"></i> Análise por IA
                    @else
                        <i class="fa-solid fa-chalkboard-user"></i> Correção por {{ $analysis->analyzer->name ?? 'Professor' }}
                    @endif
                </span>
                <span class="score-badge score-badge--{{ $analysis->score >= 600 ? 'high' : ($analysis->score >= 400 ? 'mid' : 'low') }}">
                    {{ $analysis->score }}/1000
                </span>
            </div>

            {{-- Competency Bars --}}
            <div style="margin-bottom: 1.5rem;">
                @php
                    $competencies = [
                        1 => 'Domínio da norma culta',
                        2 => 'Compreensão do tema',
                        3 => 'Argumentação',
                        4 => 'Coesão textual',
                        5 => 'Conclusão / Proposta',
                    ];
                @endphp
                @foreach($competencies as $num => $label)
                    @php $val = $analysis->{'competency_'.$num} ?? 0; @endphp
                    <div style="margin-bottom: 0.75rem;">
                        <div class="flex-between" style="margin-bottom: 4px;">
                            <span style="font-size: 0.85rem; font-weight: 600;">{{ $label }}</span>
                            <span style="font-size: 0.85rem; color: #757575;">{{ $val }}/200</span>
                        </div>
                        <div style="height: 8px; background: #E0E0E0; border-radius: 100px; overflow: hidden;">
                            <div style="height: 100%; width: {{ ($val / 200) * 100 }}%;
                                background: {{ $val >= 160 ? '#2E7D32' : ($val >= 100 ? '#F57F17' : '#D32F2F') }};
                                border-radius: 100px; transition: width 0.5s;"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="background: var(--bg-light); padding: 1rem 1.25rem; border-radius: 8px;
                        line-height: 1.8; white-space: pre-wrap; font-size: 0.92rem;">{{ $analysis->feedback }}</div>

            <p class="text-muted mt-sm" style="font-size: 0.8rem;">{{ $analysis->created_at->format('d/m/Y H:i') }}</p>
        </div>
    @endforeach

    @if($essay->analyses->isEmpty() && $essay->lineComments->isEmpty())
        <div class="card mt-lg">
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
                <div class="empty-state__text">Nenhuma análise ainda. Solicite uma análise por IA ou aguarde a correção do professor.</div>
            </div>
        </div>
    @endif

    <div class="mt-md">
        <a href="{{ route('essay.index') }}" class="btn btn--secondary">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>
</div>
@endsection

