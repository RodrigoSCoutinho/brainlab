@extends('layouts.app')

@section('title', 'Videoaulas')

@section('content')
<div class="container" style="padding: var(--sp-xl) 0;">

    <div class="page-header">
        <h1><i class="fa-solid fa-play-circle"></i> Videoaulas</h1>
        <p>Estude por tópico com vídeos selecionados para o IFRN e ENEM.</p>
    </div>

    {{-- Topic Filter --}}
    <div class="video-topics">
        <button class="video-topic video-topic--active" data-topic="all">
            <i class="fa-solid fa-border-all"></i> Todos
        </button>
        @foreach($topics as $slug => $topic)
            <button class="video-topic" data-topic="{{ $slug }}">
                <i class="fa-solid fa-{{ $topic['icon'] }}"></i> {{ $topic['name'] }}
            </button>
        @endforeach
    </div>

    {{-- Videos Grid --}}
    <div class="videos-grid" id="videosGrid">
        @foreach($videos as $video)
            <div class="video-card" data-topic="{{ $video['topic'] }}">
                <a href="https://www.youtube.com/watch?v={{ $video['youtube_id'] }}" target="_blank" rel="noopener noreferrer" class="video-card__thumb video-card__thumb--{{ $video['topic'] }}">
                    <img
                        class="video-card__thumb-img"
                        src="https://img.youtube.com/vi/{{ $video['youtube_id'] }}/hqdefault.jpg"
                        alt="{{ $video['title'] }}"
                        loading="lazy"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        onload="if (this.naturalWidth === 120) { this.style.display='none'; this.nextElementSibling.style.display='flex'; }"
                    >
                    <div class="video-card__thumb-icon" style="display: none;">
                        <i class="fa-solid fa-{{ $topics[$video['topic']]['icon'] }}"></i>
                    </div>
                    <div class="video-card__play">
                        <i class="fa-solid fa-play"></i>
                    </div>
                    <span class="video-card__duration">{{ $video['duration'] }}</span>
                </a>
                <div class="video-card__body">
                    <span class="video-card__topic-badge video-card__topic-badge--{{ $video['topic'] }}">
                        {{ $topics[$video['topic']]['name'] }}
                    </span>
                    <h3 class="video-card__title">
                        <a href="https://www.youtube.com/watch?v={{ $video['youtube_id'] }}" target="_blank" rel="noopener noreferrer">
                            {{ $video['title'] }}
                        </a>
                    </h3>
                    <p class="video-card__channel">
                        <i class="fa-brands fa-youtube"></i> {{ $video['channel'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Empty state --}}
    <div class="empty-state" id="videosEmpty" style="display: none;">
        <i class="fa-solid fa-video-slash"></i>
        <p>Nenhum vídeo encontrado para este tópico.</p>
    </div>
</div>

<script>
    document.querySelectorAll('.video-topic').forEach(btn => {
        btn.addEventListener('click', () => {
            // Toggle active
            document.querySelectorAll('.video-topic').forEach(b => b.classList.remove('video-topic--active'));
            btn.classList.add('video-topic--active');

            const topic = btn.dataset.topic;
            const cards = document.querySelectorAll('.video-card');
            let visible = 0;

            cards.forEach(card => {
                if (topic === 'all' || card.dataset.topic === topic) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('videosEmpty').style.display = visible === 0 ? '' : 'none';
        });
    });
</script>
@endsection
