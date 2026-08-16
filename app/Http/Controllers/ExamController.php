<?php

namespace App\Http\Controllers;

use App\Services\ExamService;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function __construct(
        private ExamService $examService
    ) {}

    public function index()
    {
        return view('exam.index');
    }

    public function start(Request $request)
    {
        $request->validate([
            'question_count' => ['required', 'integer', 'min:5', 'max:20'],
        ]);

        $exam = $this->examService->startExam(Auth::user(), $request->question_count);

        return redirect()->route('exam.show', $exam);
    }

    public function startProITEC(Request $request)
    {
        $exam = $this->examService->startProITECExam(Auth::user());

        return redirect()->route('exam.show', $exam);
    }

    public function show(Exam $exam)
    {
        if ($exam->user_id !== Auth::id()) {
            abort(403);
        }

        if ($exam->finished_at) {
            return redirect()->route('exam.result', $exam);
        }

        $exam->load('answers.question');

        return view('exam.show', compact('exam'));
    }

    public function submit(Request $request, Exam $exam)
    {
        if ($exam->user_id !== Auth::id()) {
            abort(403);
        }

        if ($exam->finished_at) {
            return redirect()->route('exam.result', $exam);
        }

        $answers = $request->input('answers', []);
        $this->examService->submitExam($exam, $answers);

        return redirect()->route('exam.result', $exam);
    }

    public function result(Exam $exam)
    {
        if ($exam->user_id !== Auth::id()) {
            abort(403);
        }

        $exam->load('answers.question');

        return view('exam.result', compact('exam'));
    }
}
