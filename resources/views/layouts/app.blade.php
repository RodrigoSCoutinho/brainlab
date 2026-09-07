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
        })();
    </script>
</head>
<body>
    {{-- Top loading progress bar --}}
    <div class="page-progress" id="pageProgress"></div>

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
            themeIcon.className = theme === 'dark' ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
        // Init icon
        applyTheme(localStorage.getItem('brainlab-theme') || 'light');
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });

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
