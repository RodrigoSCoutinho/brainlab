@extends('layouts.app')

@section('title', 'Design System')

@php
    $institutional = [
        ['var' => '--ifrn-green', 'hex' => '#006633'],
        ['var' => '--ifrn-green-dark', 'hex' => '#004d26'],
        ['var' => '--ifrn-green-light', 'hex' => '#2e8b57'],
        ['var' => '--ifrn-red', 'hex' => '#cc0000'],
        ['var' => '--ifrn-red-dark', 'hex' => '#a30000'],
        ['var' => '--ifrn-red-light', 'hex' => '#ffebee'],
    ];
    $neutral = [
        ['var' => '--bg-light', 'hex' => '#f4f6f8'],
        ['var' => '--bg-white', 'hex' => '#ffffff'],
        ['var' => '--text-dark', 'hex' => '#1a1a1a'],
        ['var' => '--text-muted', 'hex' => '#6c757d'],
        ['var' => '--border-color', 'hex' => '#dee2e6'],
    ];
    $semantic = [
        ['var' => '--success', 'hex' => '#006633'],
        ['var' => '--danger', 'hex' => '#cc0000'],
        ['var' => '--warning', 'hex' => '#e6a800'],
    ];

    $componentGroups = [
        'Botões' => ['.btn', '.btn--primary', '.btn--secondary', '.btn--danger', '.btn--block', '.btn--lg'],
        'Cards' => ['.card', '.stat-card', '.feature-card', '.plan-card', '.summary-card'],
        'Badges' => ['.role-badge', '.score-badge', '.plan-card__badge'],
        'Formulários' => ['.form-group', '.card-form__group', '.card-form__row', '.checkbox-group'],
        'Feedback e mensagens' => ['.feedback', '.toast', '.toast--success', '.toast--error', '.empty-state'],
        'Navegação' => ['.video-topics', '.app-footer__links', '.quick-actions'],
        'Redação' => ['.essay-paper', '.essay-paper__row', '.essay-paper__cell', '.line-annotation'],
        'Simulado' => ['.exam-timer', '.exam-table', '.question__statement', '.question__options'],
        'Checkout fictício' => ['.plans-grid', '.pix-box', '.boleto-box', '.checkout__grid'],
    ];
@endphp

@section('content')
<div class="container" style="padding: var(--sp-xl) 0; max-width: 960px;">

    <div class="page-header">
        <h1><i class="fa-solid fa-swatchbook"></i> Design System do BrainLab</h1>
        <p>Guia de identidade visual e componentes de interface utilizados na plataforma.</p>
    </div>

    {{-- Apresentação --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-book-open"></i> Apresentação</div>
        <p>
            Este guia documenta as diretrizes visuais adotadas no desenvolvimento do BrainLab: cores, tipografia,
            iconografia e os componentes reutilizados nas telas do sistema. O objetivo é manter uma identidade
            visual consistente em toda a aplicação, alinhada à paleta institucional do IFRN, sem depender de um
            framework de componentes de terceiros. Toda a interface é construída com Blade Templates e uma única
            folha de estilo CSS (<code>resources/css/app.css</code>), estruturada com variáveis personalizadas.
        </p>
    </div>

    {{-- Marca --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-brain"></i> Marca</div>
        <p>
            A marca BrainLab é representada pelo ícone <code>fa-brain</code> da biblioteca FontAwesome, combinado
            ao nome do produto em tipografia regular. Não é utilizado nenhum arquivo de imagem para o logotipo,
            mantendo a marca leve e escalável em qualquer resolução de tela.
        </p>
        <div style="display:flex; align-items:center; gap: var(--sp-sm); font-size:1.5rem; padding: var(--sp-md); background: var(--bg-light); border-radius: var(--radius-md); width: fit-content;">
            <i class="fa-solid fa-brain" style="color: var(--ifrn-green);"></i>
            <strong>BrainLab</strong>
        </div>
    </div>

    {{-- Cores --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-palette"></i> Cores</div>
        <p>A paleta é definida inteiramente por variáveis CSS (<code>:root</code>), divididas em três grupos.</p>

        <h4 style="margin-top: var(--sp-lg);">Institucional (IFRN)</h4>
        <div style="display:flex; flex-wrap:wrap; gap: var(--sp-md); margin-top: var(--sp-sm);">
            @foreach($institutional as $c)
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:56px; height:56px; border-radius: var(--radius-sm); background: {{ $c['hex'] }}; box-shadow: var(--shadow-sm);"></div>
                    <code style="font-size:0.7rem;">{{ $c['var'] }}</code>
                    <code style="font-size:0.7rem; color: var(--text-muted);">{{ $c['hex'] }}</code>
                </div>
            @endforeach
        </div>

        <h4 style="margin-top: var(--sp-lg);">Neutra</h4>
        <div style="display:flex; flex-wrap:wrap; gap: var(--sp-md); margin-top: var(--sp-sm);">
            @foreach($neutral as $c)
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:56px; height:56px; border-radius: var(--radius-sm); background: {{ $c['hex'] }}; border:1px solid var(--border-color); box-shadow: var(--shadow-sm);"></div>
                    <code style="font-size:0.7rem;">{{ $c['var'] }}</code>
                    <code style="font-size:0.7rem; color: var(--text-muted);">{{ $c['hex'] }}</code>
                </div>
            @endforeach
        </div>

        <h4 style="margin-top: var(--sp-lg);">Semântica</h4>
        <div style="display:flex; flex-wrap:wrap; gap: var(--sp-md); margin-top: var(--sp-sm);">
            @foreach($semantic as $c)
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:56px; height:56px; border-radius: var(--radius-sm); background: {{ $c['hex'] }}; box-shadow: var(--shadow-sm);"></div>
                    <code style="font-size:0.7rem;">{{ $c['var'] }}</code>
                    <code style="font-size:0.7rem; color: var(--text-muted);">{{ $c['hex'] }}</code>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Iconografia --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-icons"></i> Iconografia</div>
        <p>
            A iconografia do BrainLab vem integralmente da biblioteca <strong>FontAwesome 6.5.1</strong>, carregada
            via CDN. Nenhuma imagem é utilizada na interface, apenas ícones vetoriais, o que mantém as páginas leves
            e reduz o volume de dados transferidos, requisito relevante para o público-alvo descrito na seção 3.3.
        </p>
        <div style="display:flex; gap: var(--sp-lg); font-size:1.8rem; color: var(--ifrn-green); margin-top: var(--sp-sm);">
            <i class="fa-solid fa-brain"></i>
            <i class="fa-solid fa-play-circle"></i>
            <i class="fa-solid fa-pen-to-square"></i>
            <i class="fa-solid fa-clock"></i>
            <i class="fa-solid fa-check-double"></i>
            <i class="fa-brands fa-youtube"></i>
        </div>
    </div>

    {{-- Tipografia --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-font"></i> Tipografia</div>
        <p>
            O sistema utiliza a fonte padrão do sistema operacional do usuário, por meio de uma pilha de fontes
            (<code>--font-family</code>), priorizando <strong>Segoe UI</strong> e alternativas equivalentes em
            outras plataformas. Essa escolha evita o carregamento de arquivos de fonte externos, mantendo a
            aplicação leve.
        </p>
        <code style="display:block; margin-bottom: var(--sp-md); color: var(--text-muted); font-size:0.8rem;">
            'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif
        </code>
        <p style="font-weight:400;">Regular: Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz</p>
        <p style="font-weight:700;">Bold: Aa Bb Cc Dd Ee Ff Gg Hh Ii Jj Kk Ll Mm Nn Oo Pp Qq Rr Ss Tt Uu Vv Ww Xx Yy Zz</p>
    </div>

    {{-- Componentes --}}
    <div class="card" style="margin-bottom: var(--sp-xl);">
        <div class="card__header"><i class="fa-solid fa-shapes"></i> Componentes</div>
        <p>Principais classes de componentes reutilizados nas telas do sistema, agrupados por categoria.</p>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: var(--sp-md); margin-top: var(--sp-sm);">
            @foreach($componentGroups as $group => $classes)
                <div style="background: var(--bg-light); border-radius: var(--radius-md); padding: var(--sp-md);">
                    <strong style="font-size:0.9rem;">{{ $group }}</strong>
                    <ul style="margin: var(--sp-sm) 0 0; padding-left: 1.1rem; font-size:0.8rem; color: var(--text-muted);">
                        @foreach($classes as $class)
                            <li><code>{{ $class }}</code></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
