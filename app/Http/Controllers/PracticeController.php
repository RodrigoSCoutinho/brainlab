<?php

namespace App\Http\Controllers;

use App\Services\QuestionService;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public function __construct(
        private QuestionService $questionService
    ) {}

    public function index(Request $request)
    {
        $subjects = $this->questionService->getAllSubjects();
        $selectedSubject = $request->query('subject');

        if ($selectedSubject && !in_array($selectedSubject, $subjects, true)) {
            $selectedSubject = null;
        }

        $question = $this->questionService->getRandomQuestion($selectedSubject);

        return view('practice.index', compact('question', 'subjects', 'selectedSubject'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'selected_option' => ['required', 'in:a,b,c,d'],
            'subject' => ['nullable', 'string'],
        ]);

        $question = \App\Models\Question::findOrFail($request->question_id);
        $isCorrect = $request->selected_option === $question->correct_option;
        $selectedSubject = $request->input('subject');

        return view('practice.result', compact('question', 'isCorrect', 'selectedSubject'))
            ->with('selected', $request->selected_option);
    }
}
