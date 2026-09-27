<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BrainLab') — Plataforma Inteligente</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Apply saved theme immediately to prevent flash
        (function() {
            const t = localStorage.getItem('brainlab-theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);

            // Apply saved accessibility preferences immediately to prevent flash
            try {
                const a11y = JSON.parse(localStorage.getItem('brainlab-a11y') || '{}');
                const html = document.documentElement;
                ['dyslexic', 'alignLeft', 'highlightLinks', 'stopAnimations', 'hideImages', 'largeCursor', 'lineSpacing'].forEach(function(key) {
                    if (a11y[key]) html.classList.add('a11y-' + key.replace(/([A-Z])/g, '-$1').toLowerCase());
                });
                if (a11y.zoom) html.style.fontSize = a11y.zoom + '%';
                html.setAttribute('data-color-mode', a11y.colorMode || 'default');
            } catch (e) {}
        })();
    </script>
</head>
<body>
    {{-- Top loading progress bar --}}
    <div class="page-progress" id="pageProgress"></div>

    <div class="app-shell">

    {{-- AVA-style sidebar (only visible when the "ava" theme is active) --}}
    @auth
    <aside class="ava-sidebar">
        <div class="ava-sidebar__brand">
            <i class="fa-solid fa-brain"></i> Painel BrainLab
        </div>

        <nav class="ava-sidebar__nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}"><i class="fa-solid fa-dumbbell"></i> Praticar</a>
            <a href="{{ route('exam.index') }}" class="{{ request()->routeIs('exam.*') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-check"></i> Simulado</a>
            <a href="{{ route('essay.index') }}" class="{{ request()->routeIs('essay.*') ? 'active' : '' }}"><i class="fa-solid fa-pen-fancy"></i> Redações</a>
            <a href="{{ route('videos.index') }}" class="{{ request()->routeIs('videos.*') ? 'active' : '' }}"><i class="fa-solid fa-play-circle"></i> Vídeos</a>

            @if(Auth::user()->canTeach())
                <div class="ava-sidebar__divider">Professor</div>
                <a href="{{ route('professor.essays') }}" class="{{ request()->routeIs('professor.essays*') ? 'active' : '' }}"><i class="fa-solid fa-pen-fancy"></i> Corrigir Redações</a>
                <a href="{{ route('professor.students') }}" class="{{ request()->routeIs('professor.students*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Progresso dos Alunos</a>
                <a href="{{ route('professor.questions') }}" class="{{ request()->routeIs('professor.questions*') ? 'active' : '' }}"><i class="fa-solid fa-circle-question"></i> Banco de Questões</a>
                <a href="{{ route('professor.announcements') }}" class="{{ request()->routeIs('professor.announcements*') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Avisos</a>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="ava-sidebar__divider">Administração</div>
                <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved"></i> Painel Admin</a>
            @endif
        </nav>

        <div class="ava-sidebar__footer">
            <button type="button" class="a11y-trigger" title="Acessibilidade"><i class="fa-solid fa-universal-access"></i> Acessibilidade</button>
            <button type="button" class="help-trigger" title="Ajuda"><i class="fa-solid fa-circle-question"></i> Ajuda</button>
            <a href="{{ route('settings.index') }}" title="Gerenciar perfil"><i class="fa-solid fa-circle-user"></i> Meu Perfil</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> Sair</button>
            </form>
        </div>
    </aside>
    @endauth

    <div class="app-shell__main">

    <nav class="navbar">
        <a href="{{ url('/') }}" class="navbar__brand">
            <i class="fa-solid fa-brain"></i> BrainLab
        </a>

        {{-- Hamburger toggle (mobile) --}}
        <button class="navbar__toggle" id="navToggle" aria-label="Abrir menu">
            <span></span><span></span><span></span>
        </button>

        <ul class="navbar__links" id="navLinks">
            @auth
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                <li><a href="{{ route('practice.index') }}" class="{{ request()->routeIs('practice.*') ? 'active' : '' }}"><i class="fa-solid fa-dumbbell"></i> Praticar</a></li>
                <li><a href="{{ route('exam.index') }}" class="{{ request()->routeIs('exam.*') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-check"></i> Simulado</a></li>
                <li><a href="{{ route('essay.index') }}" class="{{ request()->routeIs('essay.*') ? 'active' : '' }}"><i class="fa-solid fa-pen-fancy"></i> Redações</a></li>
                <li><a href="{{ route('videos.index') }}" class="{{ request()->routeIs('videos.*') ? 'active' : '' }}"><i class="fa-solid fa-play-circle"></i> Vídeos</a></li>
                @if(Auth::user()->canTeach())
                    <li class="navbar__dropdown">
                        <a href="#" class="navbar__dropdown-toggle {{ request()->routeIs('professor.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-chalkboard-user"></i> Professor <i class="fa-solid fa-chevron-down" style="font-size:0.7rem"></i>
                        </a>
                        <ul class="navbar__dropdown-menu">
                            <li><a href="{{ route('professor.essays') }}"><i class="fa-solid fa-pen-fancy"></i> Redações</a></li>
                            <li><a href="{{ route('professor.students') }}"><i class="fa-solid fa-chart-line"></i> Progresso dos Alunos</a></li>
                            <li><a href="{{ route('professor.questions') }}"><i class="fa-solid fa-circle-question"></i> Banco de Questões</a></li>
                            <li><a href="{{ route('professor.announcements') }}"><i class="fa-solid fa-bullhorn"></i> Avisos</a></li>
                        </ul>
                    </li>
                @endif
                @if(Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved"></i> Admin</a></li>
                @endif
            @endauth
        </ul>

        <div class="navbar__actions">
            {{-- Accessibility panel trigger --}}
            <button class="theme-toggle a11y-trigger" aria-label="Acessibilidade" title="Acessibilidade">
                <i class="fa-solid fa-universal-access"></i>
            </button>

            {{-- Help panel trigger --}}
            <button class="theme-toggle help-trigger" aria-label="Ajuda" title="Ajuda">
                <i class="fa-solid fa-circle-question"></i>
            </button>

            {{-- Dark mode toggle --}}
            <button class="theme-toggle" id="themeToggle" aria-label="Alternar tema">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>

            <div class="navbar__user">
                @auth
                    <span class="navbar__user-name">
                        {{ Auth::user()->name }}
                        <span class="role-badge role-badge--{{ Auth::user()->role }}">{{ Auth::user()->roleLabel() }}</span>
                    </span>
                    <a href="{{ route('settings.index') }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;" title="Configurações">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn--secondary" style="padding: 4px 12px; font-size: 0.8rem;">Entrar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Accessibility panel (available regardless of theme) --}}
    <div class="a11y-panel" id="a11yPanel" hidden>
        <div class="a11y-panel__header">
            <span><i class="fa-solid fa-universal-access"></i> Acessibilidade</span>
            <button type="button" id="a11yClose" aria-label="Fechar"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="a11y-panel__body">
            <button type="button" class="a11y-toggle" data-a11y="dyslexic"><span class="a11y-toggle__icon">Aa</span> Fonte amigável a disléxicos</button>
            <button type="button" class="a11y-toggle" data-a11y="alignLeft"><i class="fa-solid fa-align-left"></i> Alinhar texto à esquerda</button>
            <button type="button" class="a11y-toggle" data-a11y="highlightLinks"><i class="fa-solid fa-highlighter"></i> Destacar links</button>
            <button type="button" class="a11y-toggle" data-a11y="stopAnimations"><i class="fa-solid fa-circle-pause"></i> Parar animações</button>
            <button type="button" class="a11y-toggle" data-a11y="hideImages"><i class="fa-solid fa-image-slash"></i> Ocultar imagens ilustrativas</button>
            <button type="button" class="a11y-toggle" data-a11y="largeCursor"><i class="fa-solid fa-arrow-pointer"></i> Cursor do mouse grande</button>
            <button type="button" class="a11y-toggle" data-a11y="vlibras"><i class="fa-solid fa-hands"></i> Habilitar VLibras</button>
            <button type="button" class="a11y-toggle" data-a11y="lineSpacing"><i class="fa-solid fa-arrows-up-down"></i> Linhas mais distantes</button>

            <div class="a11y-panel__section">
                <span class="a11y-panel__label">Zoom: <strong id="a11yZoomValue">100%</strong></span>
                <input type="range" id="a11yZoom" min="80" max="150" step="10" value="100">
            </div>

            <div class="a11y-panel__section">
                <span class="a11y-panel__label">Modo de cor</span>
                <div class="a11y-color-modes">
                    <button type="button" class="a11y-color-mode" data-mode="default">Padrão</button>
                    <button type="button" class="a11y-color-mode" data-mode="reduced-contrast">Contraste reduzido</button>
                    <button type="button" class="a11y-color-mode" data-mode="colorblind">Amigável a daltônicos</button>
                    <button type="button" class="a11y-color-mode" data-mode="grayscale">Escala de cinza</button>
                    <button type="button" class="a11y-color-mode" data-mode="high-contrast">Alto contraste</button>
                </div>
            </div>
        </div>
    </div>
    <div id="vlibrasWrapper"></div>

    {{-- Help panel (available regardless of theme) --}}
    <div class="help-panel" id="helpPanel" hidden>
        <div class="help-panel__header">
            <span><i class="fa-solid fa-circle-question"></i> Ajuda</span>
            <button type="button" id="helpClose" aria-label="Fechar"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="help-panel__body">
            <div class="help-panel__section-title">Central de Ajuda</div>

            <details class="help-faq">
                <summary>Como funciona o modo prática?</summary>
                <p>No modo prática você responde uma questão de múltipla escolha por vez e recebe feedback imediato, sem cronômetro. Ideal para estudar um assunto específico no seu próprio ritmo.</p>
            </details>
            <details class="help-faq">
                <summary>Como funciona o simulado cronometrado?</summary>
                <p>O simulado reúne várias questões aleatórias com um cronômetro regressivo, reproduzindo as condições da prova real. Ao final, você recebe a correção automática e pode revisar o gabarito.</p>
            </details>
            <details class="help-faq">
                <summary>Como funciona a correção de redação?</summary>
                <p>Você envia sua redação pela plataforma e ela fica disponível para o professor corrigir, com nota por competência (0 a 200 cada) e comentários em trechos específicos do texto.</p>
            </details>
            <details class="help-faq">
                <summary>Esqueci minha senha, o que faço?</summary>
                <p>No momento, a recuperação de senha não está disponível automaticamente. Entre em contato pelo e-mail abaixo.</p>
            </details>

            <div class="help-panel__section-title">Contato</div>
            <a href="mailto:suporte@brainlab.com" class="help-panel__contact">
                <i class="fa-solid fa-envelope"></i> suporte@brainlab.com
            </a>

            <p class="help-panel__disclaimer">
                O BrainLab é um projeto acadêmico independente (TCC), desenvolvido para fins educacionais. Não é um sistema oficial do IFRN.
            </p>
        </div>
    </div>

    {{-- Toast notifications --}}
    @if(session('success'))
        <div class="toast toast--success" id="toast">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button class="toast__close" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="toast toast--error" id="toast">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>{{ session('error') }}</span>
            <button class="toast__close" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="app-footer">
        <div class="app-footer__inner">
            <div class="app-footer__brand">
                <i class="fa-solid fa-brain"></i> BrainLab
            </div>
            <p class="app-footer__text">Plataforma de estudos para o IFRN &mdash; Projeto TCC</p>
            <div class="app-footer__links">
                <a href="{{ url('/') }}">Início</a>
                <span>&middot;</span>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span>&middot;</span>
                <a href="{{ route('videos.index') }}">Vídeos</a>
                <span>&middot;</span>
                <a href="{{ url('/design-system') }}">Design System</a>
            </div>

            <div class="app-footer__info">
                <span><strong>Framework:</strong> Laravel 9.19 (PHP 8.0.2+)</span>
                <span>&middot;</span>
                <span><strong>Banco de dados:</strong> MySQL 8.0 (Docker)</span>
                <span>&middot;</span>
                <span><strong>Build:</strong> Vite 4</span>
                <span>&middot;</span>
                <span><strong>Responsável:</strong> Rodrigo Coutinho</span>
            </div>

        </div>
    </footer>

    </div>{{-- /.app-shell__main --}}
    </div>{{-- /.app-shell --}}

    <script>
        // === Hamburger menu ===
        const navToggle = document.getElementById('navToggle');
        const navLinks = document.getElementById('navLinks');
        if (navToggle) {
            navToggle.addEventListener('click', () => {
                navToggle.classList.toggle('active');
                navLinks.classList.toggle('open');
            });
            // Close menu when clicking a link
            navLinks.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    navToggle.classList.remove('active');
                    navLinks.classList.remove('open');
                });
            });
        }

        // === Dark mode toggle ===
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('brainlab-theme', theme);
            themeIcon.className = theme === 'dark'
                ? 'fa-solid fa-sun'
                : (theme === 'ava' ? 'fa-solid fa-swatchbook' : 'fa-solid fa-moon');

            // Keep the theme picker in Settings (if present on this page) in sync
            const radio = document.querySelector('input[name="theme"][value="' + theme + '"]');
            if (radio) radio.checked = true;
        }
        // Init icon
        applyTheme(localStorage.getItem('brainlab-theme') || 'light');
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            // The quick toggle only cycles light/dark; leaving the "ava" theme
            // is done explicitly from the picker in Configurações.
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });

        // === Theme picker (Configurações page) ===
        document.querySelectorAll('input[name="theme"]').forEach(input => {
            if (input.value === (localStorage.getItem('brainlab-theme') || 'light')) {
                input.checked = true;
            }
            input.addEventListener('change', () => applyTheme(input.value));
        });

        // === Accessibility panel ===
        (function() {
            const panel = document.getElementById('a11yPanel');
            const defaults = {
                dyslexic: false, alignLeft: false, highlightLinks: false, stopAnimations: false,
                hideImages: false, largeCursor: false, vlibras: false, lineSpacing: false,
                zoom: 100, colorMode: 'default'
            };

            function loadState() {
                try {
                    return Object.assign({}, defaults, JSON.parse(localStorage.getItem('brainlab-a11y') || '{}'));
                } catch (e) {
                    return Object.assign({}, defaults);
                }
            }
            function saveState(state) {
                localStorage.setItem('brainlab-a11y', JSON.stringify(state));
            }

            let vlibrasLoaded = false;
            function loadVLibras() {
                if (vlibrasLoaded) return;
                vlibrasLoaded = true;
                const wrapper = document.getElementById('vlibrasWrapper');
                wrapper.innerHTML = '<div vw class="enabled"><div vw-access-button class="active"></div>'
                    + '<div vw-plugin-wrapper><div class="vw-plugin-top-wrapper"></div></div></div>';
                const script = document.createElement('script');
                script.src = 'https://vlibras.gov.br/app/vlibras-plugin.js';
                script.onload = function() {
                    if (window.VLibras) new window.VLibras.Widget('https://vlibras.gov.br/app');
                };
                document.body.appendChild(script);
            }

            function applyState(state) {
                const html = document.documentElement;
                ['dyslexic', 'alignLeft', 'highlightLinks', 'stopAnimations', 'hideImages', 'largeCursor', 'lineSpacing'].forEach(function(key) {
                    html.classList.toggle('a11y-' + key.replace(/([A-Z])/g, '-$1').toLowerCase(), !!state[key]);
                });
                html.style.fontSize = state.zoom + '%';
                html.setAttribute('data-color-mode', state.colorMode || 'default');

                document.querySelectorAll('.a11y-toggle').forEach(function(btn) {
                    btn.classList.toggle('a11y-toggle--active', !!state[btn.dataset.a11y]);
                });
                document.querySelectorAll('.a11y-color-mode').forEach(function(btn) {
                    btn.classList.toggle('a11y-color-mode--active', btn.dataset.mode === (state.colorMode || 'default'));
                });

                const zoomLabel = document.getElementById('a11yZoomValue');
                if (zoomLabel) zoomLabel.textContent = state.zoom + '%';
                const zoomInput = document.getElementById('a11yZoom');
                if (zoomInput) zoomInput.value = state.zoom;

                if (state.vlibras) loadVLibras();
            }

            let state = loadState();
            applyState(state);

            document.querySelectorAll('.a11y-trigger').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    panel.hidden = !panel.hidden;
                });
            });
            const closeBtn = document.getElementById('a11yClose');
            if (closeBtn) closeBtn.addEventListener('click', function() { panel.hidden = true; });

            document.querySelectorAll('.a11y-toggle').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const key = btn.dataset.a11y;
                    state[key] = !state[key];
                    saveState(state);
                    applyState(state);
                });
            });

            const zoomInput = document.getElementById('a11yZoom');
            if (zoomInput) {
                zoomInput.addEventListener('input', function(e) {
                    state.zoom = parseInt(e.target.value, 10);
                    saveState(state);
                    applyState(state);
                });
            }

            document.querySelectorAll('.a11y-color-mode').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    state.colorMode = btn.dataset.mode;
                    saveState(state);
                    applyState(state);
                });
            });
        })();

        // === Help panel ===
        (function() {
            const panel = document.getElementById('helpPanel');
            if (!panel) return;

            document.querySelectorAll('.help-trigger').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    panel.hidden = !panel.hidden;
                });
            });
            const closeBtn = document.getElementById('helpClose');
            if (closeBtn) closeBtn.addEventListener('click', function() { panel.hidden = true; });
        })();

        // === Page progress bar ===
        const progress = document.getElementById('pageProgress');
        if (progress) {
            progress.style.width = '70%';
            window.addEventListener('load', () => {
                progress.style.width = '100%';
                setTimeout(() => { progress.style.opacity = '0'; }, 300);
            });
        }

        // === Auto-dismiss toasts ===
        document.querySelectorAll('.toast').forEach(toast => {
            setTimeout(() => {
                toast.style.animation = 'toastOut 0.4s ease forwards';
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        });
    </script>
</body>
</html>
