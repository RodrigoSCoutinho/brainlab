<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Services\GamificationService;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    public function __construct(
        private QuestionService $questionService,
        private GamificationService $gamificationService
    ) {}

    public function index(Request $request)
    {
        $subjects = $this->questionService->getAllSubjects();
        $selectedSubject = $request->query('subject');

        if ($selectedSubject && !in_array($selectedSubject, $subjects, true)) {
            $selectedSubject = null;
        }

        if (!$request->session()->has('practice_hearts')) {
            $request->session()->put('practice_hearts', GamificationService::STARTING_HEARTS);
        }

        $hearts = $request->session()->get('practice_hearts');

        if ($hearts <= 0) {
            return view('practice.game-over', [
                'selectedSubject' => $selectedSubject,
            ]);
        }

        $question = $this->questionService->getRandomQuestion($selectedSubject);

        return view('practice.index', compact('question', 'subjects', 'selectedSubject', 'hearts'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['required', 'in:a,b,c,d'],
            'subject' => ['nullable', 'string'],
        ]);

        $question = Question::findOrFail($request->question_id);
        $isCorrect = $request->selected_option === $question->correct_option;
        $selectedSubject = $request->input('subject');

        $this->gamificationService->recordAttempt(
            Auth::user(),
            $question->id,
            $question->subject,
            $isCorrect
        );

        $hearts = $request->session()->get('practice_hearts', GamificationService::STARTING_HEARTS);
        if (!$isCorrect) {
            $hearts = max(0, $hearts - 1);
            $request->session()->put('practice_hearts', $hearts);
        }

        return view('practice.result', compact('question', 'isCorrect', 'selectedSubject', 'hearts'))
            ->with('selected', $request->selected_option)
            ->with('xpGained', $isCorrect ? GamificationService::XP_PER_CORRECT_ANSWER : 0);
    }

    public function restart(Request $request)
    {
        $request->session()->put('practice_hearts', GamificationService::STARTING_HEARTS);

        return redirect()->route('practice.index', ['subject' => $request->input('subject')]);
    }
}
