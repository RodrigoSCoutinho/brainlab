<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\EssayController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\SettingsController;

// ---------- Public ----------
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : view('landing');
});

Route::get('/design-system', function () {
    return view('design-system');
});

Route::get('/checkout/{plan}', function (string $plan) {
    $plans = [
        'pro' => [
            'name' => 'Pro',
            'slug' => 'pro',
            'price' => 'R$ 19,00/mês',
            'icon' => 'bolt',
            'features' => [
                'Tudo do plano Estudante',
                'Análise de redação por IA',
                'Nota por critério de escrita',
                'Sugestões de melhoria personalizadas',
                'Redações ilimitadas',
                'Correção por professor',
                'Vídeos exclusivos das disciplinas',
            ],
        ],
    ];

    if (!isset($plans[$plan])) {
        abort(404);
    }

    return view('checkout', ['plan' => $plans[$plan]]);
})->name('checkout');

// ---------- Auth (guest only) ----------
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ---------- Authenticated ----------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::post('/practice', [PracticeController::class, 'check'])->name('practice.check');

    Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
    Route::post('/exam', [ExamController::class, 'start'])->name('exam.start');
    Route::post('/exam/proitec', [ExamController::class, 'startProITEC'])->name('exam.proitec');
    Route::get('/exam/{exam}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/exam/{exam}/submit', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam/{exam}/result', [ExamController::class, 'result'])->name('exam.result');

    // --- Essays (Student) ---
    Route::get('/essays', [EssayController::class, 'index'])->name('essay.index');
    Route::get('/essays/create', [EssayController::class, 'create'])->name('essay.create');
    Route::post('/essays', [EssayController::class, 'store'])->name('essay.store');
    Route::get('/essays/{essay}', [EssayController::class, 'show'])->name('essay.show');
    Route::post('/essays/{essay}/analyze-ai', [EssayController::class, 'analyzeAI'])->name('essay.analyze-ai');

    // --- Settings ---
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('/settings/openai', [SettingsController::class, 'updateOpenAI'])->name('settings.openai');

    // --- Professor panel (professor + admin) ---
    Route::middleware('role:professor,admin')->group(function () {
        // Essays
        Route::get('/professor/essays', [EssayController::class, 'professorIndex'])->name('professor.essays');
        Route::get('/professor/essays/{essay}/analyze', [EssayController::class, 'professorAnalyzeForm'])->name('professor.essays.analyze');
        Route::post('/professor/essays/{essay}/analyze', [EssayController::class, 'professorAnalyzeStore'])->name('professor.essays.analyze.store');
        Route::post('/professor/essays/{essay}/lines', [EssayController::class, 'lineCommentStore'])->name('professor.essays.lines.store');
        Route::delete('/professor/essays/{essay}/lines/{comment}', [EssayController::class, 'lineCommentDestroy'])->name('professor.essays.lines.destroy');

        // Students progress
        Route::get('/professor/students', [ProfessorController::class, 'students'])->name('professor.students');
        Route::get('/professor/students/{student}', [ProfessorController::class, 'studentDetail'])->name('professor.students.show');

        // Question bank
        Route::get('/professor/questions', [ProfessorController::class, 'questions'])->name('professor.questions');
        Route::get('/professor/questions/create', [ProfessorController::class, 'questionsCreate'])->name('professor.questions.create');
        Route::post('/professor/questions', [ProfessorController::class, 'questionsStore'])->name('professor.questions.store');
        Route::get('/professor/questions/{question}/edit', [ProfessorController::class, 'questionsEdit'])->name('professor.questions.edit');
        Route::put('/professor/questions/{question}', [ProfessorController::class, 'questionsUpdate'])->name('professor.questions.update');
        Route::delete('/professor/questions/{question}', [ProfessorController::class, 'questionsDestroy'])->name('professor.questions.destroy');

        // Announcements
        Route::get('/professor/announcements', [ProfessorController::class, 'announcements'])->name('professor.announcements');
        Route::post('/professor/announcements', [ProfessorController::class, 'announcementsStore'])->name('professor.announcements.store');
        Route::delete('/professor/announcements/{announcement}', [ProfessorController::class, 'announcementsDestroy'])->name('professor.announcements.destroy');
    });

    // --- Admin panel (admin only) ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::put('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    });

    // --- Videos ---
    Route::get('/videos', function () {
        $subjects = \App\Models\Question::distinct()->pluck('subject')->sort()->filter()->values()->toArray();

        $topics = [];
        foreach ($subjects as $subject) {
            $slug = \Illuminate\Support\Str::slug($subject, '-');
            $topics[$slug] = [
                'name' => $subject,
                'icon' => match ($subject) {
                    'Matemática' => 'square-root-variable',
                    'Língua Portuguesa' => 'book-open',
                    'Ética e Cidadania' => 'hands-holding-heart',
                    default => 'book-open',
                },
            ];
        }

        $videos = [
            // Matemática
            ['topic' => 'matematica', 'youtube_id' => 'pBKmSIimSJY', 'title' => 'Porcentagem - Professora Angela Matemática', 'channel' => 'Professora Angela', 'duration' => '22:06'],
            ['topic' => 'matematica', 'youtube_id' => 'GjmtEQpPVSQ', 'title' => 'Frações: adição, subtração, multiplicação e divisão', 'channel' => 'Matemática Rio', 'duration' => '13:48'],
            ['topic' => 'matematica', 'youtube_id' => 'JpFjGo4wJWs', 'title' => 'Áreas de figuras planas - resumo completo', 'channel' => 'Matemática Rio', 'duration' => '18:35'],
            ['topic' => 'matematica', 'youtube_id' => 'r1p3NaJOxhY', 'title' => 'Teorema de Pitágoras - aula completa', 'channel' => 'Prof. Ferretto', 'duration' => '21:10'],
            ['topic' => 'matematica', 'youtube_id' => 'aGm8_RjsLCA', 'title' => 'Média, moda e mediana - estatística básica', 'channel' => 'Matemática Rio', 'duration' => '11:30'],
            ['topic' => 'matematica', 'youtube_id' => '9M6mMXsA2cE', 'title' => 'Probabilidade e estatística para concursos', 'channel' => 'Prof. Ferretto', 'duration' => '28:15'],
            ['topic' => 'matematica', 'youtube_id' => 'cpIv-bBfylQ', 'title' => 'Potenciação - todas as propriedades', 'channel' => 'Matemática Rio', 'duration' => '15:50'],
            ['topic' => 'matematica', 'youtube_id' => 'AznSzYaJEU0', 'title' => 'Regra de três simples e composta', 'channel' => 'Matemática Rio', 'duration' => '14:22'],

            // Língua Portuguesa
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'QjPQzmOmN10', 'title' => 'Interpretação de texto - dicas infalíveis', 'channel' => 'Prof. Noslen', 'duration' => '12:18'],
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'wl86RhdAMK8', 'title' => 'Como interpretar textos em provas', 'channel' => 'Brasil Escola', 'duration' => '09:45'],
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'WR1oFbDNWR8', 'title' => 'Classes gramaticais - resumo completo', 'channel' => 'Prof. Noslen', 'duration' => '16:50'],
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'JtH5UEp9xKI', 'title' => 'Concordância verbal e nominal', 'channel' => 'Prof. Noslen', 'duration' => '14:30'],
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'tVlJmyamxdg', 'title' => 'Como fazer uma redação nota 1000', 'channel' => 'Prof. Noslen', 'duration' => '20:12'],
            ['topic' => 'lingua-portuguesa', 'youtube_id' => 'HmdgcbQ5HtQ', 'title' => 'Estrutura da redação dissertativa-argumentativa', 'channel' => 'Brasil Escola', 'duration' => '14:45'],

            // Ética e Cidadania
            ['topic' => 'etica-e-cidadania', 'youtube_id' => 'kA9gT7jYp4I', 'title' => 'Ética e cidadania: direitos e deveres', 'channel' => 'Canal Educação', 'duration' => '12:15'],
            ['topic' => 'etica-e-cidadania', 'youtube_id' => 'DPy3we78Q2U', 'title' => 'Cidadania e participação social', 'channel' => 'Brasil Escola', 'duration' => '10:48'],
        ];

        return view('videos', compact('topics', 'videos'));
    })->name('videos.index');
});
