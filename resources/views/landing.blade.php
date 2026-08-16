<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrainLab — Plataforma Inteligente de Estudos</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- ==================== NAVBAR ==================== --}}
<nav class="landing-nav" id="landingNav">
    <a href="{{ url('/') }}" class="landing-nav__brand">
        <i class="fa-solid fa-brain"></i> BrainLab
    </a>

    <ul class="landing-nav__links">
        <li><a href="#features" class="landing-nav__link">Recursos</a></li>
        <li><a href="#how" class="landing-nav__link">Como funciona</a></li>
        <li><a href="#plans" class="landing-nav__link">Planos</a></li>
    </ul>

    @auth
        <a href="{{ route('dashboard') }}" class="landing-nav__cta">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Dashboard
        </a>
    @else
        <a href="{{ route('login') }}" class="landing-nav__cta">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrar
        </a>
    @endauth
</nav>

{{-- ==================== HERO ==================== --}}
<section class="hero">
    <div class="hero__shapes">
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="hero__content scroll-reveal">
        <div class="hero__icon">
            <i class="fa-solid fa-brain"></i>
        </div>
        <h1 class="hero__title">
            Estude de forma <span>inteligente</span>
        </h1>
        <p class="hero__subtitle">
            Simulados, prática de questões e análise de redações com inteligência artificial.
            A plataforma de estudos do IFRN feita para alunos e professores.
        </p>
        <div class="hero__actions">
            <a href="{{ route('register') }}" class="hero__btn hero__btn--primary">
                <i class="fa-solid fa-rocket"></i> Começar grátis
            </a>
            <a href="#features" class="hero__btn hero__btn--ghost">
                <i class="fa-solid fa-circle-play"></i> Saiba mais
            </a>
        </div>
    </div>

    <div class="hero__scroll-hint">
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>

{{-- ==================== FEATURES ==================== --}}
<section class="landing-section" id="features">
    <h2 class="landing-section__title">Tudo que você precisa para estudar</h2>
    <p class="landing-section__subtitle">
        Ferramentas pensadas para alunos e professores que querem resultados reais.
    </p>

    <div class="features-grid">
        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <h3 class="feature-card__title">Simulados Completos</h3>
            <p class="feature-card__desc">
                Gere simulados personalizados com questões de múltipla escolha e acompanhe seu desempenho ao longo do tempo.
            </p>
        </div>

        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <h3 class="feature-card__title">Modo Prática</h3>
            <p class="feature-card__desc">
                Resolva questões uma a uma com feedback instantâneo e explicações detalhadas para cada resposta.
            </p>
        </div>

        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-pen-fancy"></i>
            </div>
            <h3 class="feature-card__title">Análise de Redações</h3>
            <p class="feature-card__desc">
                Envie suas redações e receba análises detalhadas por IA ou pelo seu professor, com nota por competência.
            </p>
        </div>

        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-robot"></i>
            </div>
            <h3 class="feature-card__title">IA Integrada</h3>
            <p class="feature-card__desc">
                Inteligência artificial analisa suas redações por critérios de escrita e argumentação e sugere melhorias personalizadas.
            </p>
        </div>

        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <h3 class="feature-card__title">Painel do Professor</h3>
            <p class="feature-card__desc">
                Professores acompanham o progresso dos alunos, corrigem redações e gerenciam turmas.
            </p>
        </div>

        <div class="feature-card scroll-reveal">
            <div class="feature-card__icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <h3 class="feature-card__title">Dashboard de Progresso</h3>
            <p class="feature-card__desc">
                Estatísticas detalhadas, histórico de notas e evolução visível para manter a motivação.
            </p>
        </div>
    </div>
</section>

{{-- ==================== HOW IT WORKS ==================== --}}
<section class="landing-section landing-section--alt" id="how">
    <h2 class="landing-section__title">Como funciona</h2>
    <p class="landing-section__subtitle">
        Em três passos simples você já está estudando.
    </p>

    <div class="steps-grid">
        <div class="step scroll-reveal">
            <div class="step__number">1</div>
            <h3 class="step__title">Crie sua conta</h3>
            <p class="step__desc">Cadastre-se gratuitamente como aluno ou professor em menos de 1 minuto.</p>
        </div>

        <div class="step scroll-reveal">
            <div class="step__number">2</div>
            <h3 class="step__title">Escolha como estudar</h3>
            <p class="step__desc">Pratique questões, faça simulados ou envie redações para análise.</p>
        </div>

        <div class="step scroll-reveal">
            <div class="step__number">3</div>
            <h3 class="step__title">Acompanhe seu progresso</h3>
            <p class="step__desc">Veja suas estatísticas, evolua e se destaque nas disciplinas do IFRN.</p>
        </div>
    </div>
</section>

{{-- ==================== PLANS ==================== --}}
<section class="landing-section" id="plans">
    <h2 class="landing-section__title">Escolha seu plano</h2>
    <p class="landing-section__subtitle">
        Alunos começam grátis. Professores têm acesso institucional gratuito.
    </p>

    <div class="plans-grid">
        {{-- Free --}}
        <div class="plan-card scroll-reveal">
            <div class="plan-card__icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <h3 class="plan-card__name">Estudante</h3>
            <div class="plan-card__price">Grátis</div>
            <p class="plan-card__desc">Ideal para começar a estudar</p>
            <ul class="plan-card__features">
                <li><i class="fa-solid fa-check"></i> Simulados ilimitados</li>
                <li><i class="fa-solid fa-check"></i> Modo prática com explicações</li>
                <li><i class="fa-solid fa-check"></i> Dashboard de progresso</li>
                <li><i class="fa-solid fa-check"></i> Histórico completo</li>
                <li class="disabled"><i class="fa-solid fa-xmark"></i> Análise de redação por IA</li>
                <li class="disabled"><i class="fa-solid fa-xmark"></i> Correção por professor</li>
                <li class="disabled"><i class="fa-solid fa-xmark"></i> Vídeos exclusivos</li>
            </ul>
            <a href="{{ route('register') }}" class="plan-card__btn plan-card__btn--outline">
                Começar grátis
            </a>
        </div>

        {{-- Pro --}}
        <div class="plan-card plan-card--featured scroll-reveal">
            <div class="plan-card__badge">Popular</div>
            <div class="plan-card__icon"><i class="fa-solid fa-bolt"></i></div>
            <h3 class="plan-card__name">Pro</h3>
            <div class="plan-card__price">R$ 19<small>/mês</small></div>
            <p class="plan-card__desc">Para alunos que querem ir além</p>
            <ul class="plan-card__features">
                <li><i class="fa-solid fa-check"></i> Tudo do plano Estudante</li>
                <li><i class="fa-solid fa-check"></i> Análise de redação por IA</li>
                <li><i class="fa-solid fa-check"></i> Nota por critério de escrita</li>
                <li><i class="fa-solid fa-check"></i> Sugestões de melhoria personalizadas</li>
                <li><i class="fa-solid fa-check"></i> Redações ilimitadas</li>
                <li><i class="fa-solid fa-check"></i> Correção por professor</li>
                <li><i class="fa-solid fa-check"></i> Vídeos exclusivos das disciplinas</li>
            </ul>
            <a href="{{ route('checkout', 'pro') }}" class="plan-card__btn plan-card__btn--primary">
                Assinar agora
            </a>
        </div>

        {{-- Professor --}}
        <div class="plan-card scroll-reveal">
            <div class="plan-card__icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <h3 class="plan-card__name">Professor</h3>
            <div class="plan-card__price">Gratuito<small> institucional</small></div>
            <p class="plan-card__desc">Para docentes do IFRN</p>
            <ul class="plan-card__features">
                <li><i class="fa-solid fa-check"></i> Acesso completo à plataforma</li>
                <li><i class="fa-solid fa-check"></i> Painel de turmas</li>
                <li><i class="fa-solid fa-check"></i> Corrigir redações dos alunos</li>
                <li><i class="fa-solid fa-check"></i> Banco de questões próprio</li>
                <li><i class="fa-solid fa-check"></i> Acompanhar progresso dos alunos</li>
                <li><i class="fa-solid fa-check"></i> Publicar avisos e conteúdos</li>
            </ul>
            <a href="mailto:admin@brainlab.ifrn.edu.br" class="plan-card__btn plan-card__btn--outline">
                Solicitar acesso
            </a>
        </div>
    </div>
</section>

{{-- ==================== FOOTER ==================== --}}
<footer class="landing-footer">
    <div class="landing-footer__brand">
        <i class="fa-solid fa-brain"></i> BrainLab
    </div>
    <p>Plataforma inteligente de estudos do IFRN para alunos e professores.</p>

    <ul class="landing-footer__links">
        <li><a href="#features">Recursos</a></li>
        <li><a href="#how">Como funciona</a></li>
        <li><a href="#plans">Planos</a></li>
        <li><a href="{{ route('login') }}">Entrar</a></li>
    </ul>

    <p class="landing-footer__copy">
        &copy; {{ date('Y') }} BrainLab — IFRN. Todos os direitos reservados.
    </p>
</footer>

{{-- ==================== SCRIPTS ==================== --}}
<script>
    // Navbar scroll effect
    const nav = document.getElementById('landingNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('is-scrolled', window.scrollY > 60);
    });

    // Scroll reveal (IntersectionObserver)
    const reveals = document.querySelectorAll('.scroll-reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    reveals.forEach(el => observer.observe(el));

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
</body>
</html>
