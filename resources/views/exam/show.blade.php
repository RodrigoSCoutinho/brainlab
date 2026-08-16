@extends('layouts.app')

@section('title', 'Simulado em andamento')

@section('content')
<div class="container">
    <div class="exam-timer" id="exam-timer">
        <div>
            <span class="exam-timer__progress">Simulado — {{ $exam->total_questions }} questões</span>
        </div>
        <div class="exam-timer__clock" id="timer">--:--</div>
    </div>

    <form action="{{ route('exam.submit', $exam) }}" method="POST" id="exam-form">
        @csrf

        @foreach($exam->answers as $index => $answer)
            <div class="card" id="question-{{ $index }}">
                <span class="text-muted" style="font-size: 0.85rem;">
                    Questão {{ $index + 1 }} de {{ $exam->total_questions }}
                    — {{ $answer->question->subject }}
                </span>

                <div class="question__statement">
                    {{ $answer->question->statement }}
                </div>

                <ul class="question__options">
                    @foreach(['a', 'b', 'c', 'd'] as $letter)
                        <label class="option">
                            <input type="radio"
                                   name="answers[{{ $answer->question_id }}]"
                                   value="{{ $letter }}">
                            <span class="option__letter">{{ strtoupper($letter) }}</span>
                            <span>{{ $answer->question->{'option_' . $letter} }}</span>
                        </label>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <div style="text-align: center; padding: 1rem 0 2rem;">
            <button type="submit" class="btn btn--primary btn--lg" onclick="return confirm('Tem certeza que deseja finalizar o simulado?')">
                <i class="fa-solid fa-check-double"></i> Finalizar Simulado
            </button>
        </div>
    </form>
</div>

<script>
    (function() {
        const totalMinutes = {{ $exam->total_questions }} * 2;
        let totalSeconds = totalMinutes * 60;
        const timerEl = document.getElementById('timer');
        const formEl = document.getElementById('exam-form');

        function updateTimer() {
            const min = Math.floor(totalSeconds / 60);
            const sec = totalSeconds % 60;
            timerEl.textContent = String(min).padStart(2, '0') + ':' + String(sec).padStart(2, '0');

            if (totalSeconds <= 60) {
                timerEl.style.color = '#D32F2F';
            }

            if (totalSeconds <= 0) {
                clearInterval(interval);
                alert('⏰ Tempo esgotado! O simulado será enviado automaticamente.');
                formEl.submit();
                return;
            }

            totalSeconds--;
        }

        updateTimer();
        const interval = setInterval(updateTimer, 1000);
    })();
</script>
@endsection
