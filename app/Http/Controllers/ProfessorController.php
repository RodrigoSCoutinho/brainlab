<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessorController extends Controller
{
    // ── Students ─────────────────────────────────────────────────────────────

    public function students()
    {
        $students = User::where('role', User::ROLE_STUDENT)
            ->withCount(['exams', 'essays'])
            ->get()
            ->map(function (User $student) {
                $exams = $student->exams;
                $student->avg_score = $exams->isNotEmpty()
                    ? round($exams->avg(fn ($e) => $e->percentage()), 1)
                    : null;
                return $student;
            });

        return view('professor.students', compact('students'));
    }

    public function studentDetail(User $student)
    {
        if ($student->role !== User::ROLE_STUDENT) {
            abort(404);
        }

        $exams  = $student->exams()->latest()->get();
        $essays = $student->essays()->with(['analyses.analyzer'])->latest()->get();

        return view('professor.student-detail', compact('student', 'exams', 'essays'));
    }

    // ── Questions ─────────────────────────────────────────────────────────────

    public function questions()
    {
        $questions = Question::latest()->paginate(20);

        return view('professor.questions', compact('questions'));
    }

    public function questionsCreate()
    {
        return view('professor.questions-create');
    }

    public function questionsStore(Request $request)
    {
        $data = $request->validate([
            'statement'      => ['required', 'string', 'min:10'],
            'option_a'       => ['required', 'string', 'max:500'],
            'option_b'       => ['required', 'string', 'max:500'],
            'option_c'       => ['required', 'string', 'max:500'],
            'option_d'       => ['required', 'string', 'max:500'],
            'correct_option' => ['required', 'in:a,b,c,d'],
            'subject'        => ['nullable', 'string', 'max:255'],
            'explanation'    => ['nullable', 'string'],
        ]);

        Question::create($data);

        return redirect()->route('professor.questions')
            ->with('success', 'Questão adicionada com sucesso!');
    }

    public function questionsEdit(Question $question)
    {
        return view('professor.questions-edit', compact('question'));
    }

    public function questionsUpdate(Request $request, Question $question)
    {
        $data = $request->validate([
            'statement'      => ['required', 'string', 'min:10'],
            'option_a'       => ['required', 'string', 'max:500'],
            'option_b'       => ['required', 'string', 'max:500'],
            'option_c'       => ['required', 'string', 'max:500'],
            'option_d'       => ['required', 'string', 'max:500'],
            'correct_option' => ['required', 'in:a,b,c,d'],
            'subject'        => ['nullable', 'string', 'max:255'],
            'explanation'    => ['nullable', 'string'],
        ]);

        $question->update($data);

        return redirect()->route('professor.questions')
            ->with('success', 'Questão atualizada com sucesso!');
    }

    public function questionsDestroy(Question $question)
    {
        $question->delete();

        return back()->with('success', 'Questão removida com sucesso!');
    }

    // ── Announcements ─────────────────────────────────────────────────────────

    public function announcements()
    {
        $announcements = Announcement::with('author')->latest()->get();

        return view('professor.announcements', compact('announcements'));
    }

    public function announcementsStore(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
        ]);

        Announcement::create([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'author_id' => Auth::id(),
        ]);

        return back()->with('success', 'Aviso publicado com sucesso!');
    }

    public function announcementsDestroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('success', 'Aviso removido.');
    }
}
