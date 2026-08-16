<?php

namespace App\Http\Controllers;

use App\Models\Essay;
use App\Models\EssayLineComment;
use App\Services\EssayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssayController extends Controller
{
    public function __construct(
        private EssayService $essayService
    ) {}

    /**
     * Student: list my essays.
     */
    public function index()
    {
        $essays = $this->essayService->getUserEssays(Auth::user());
        return view('essay.index', compact('essays'));
    }

    /**
     * Student: show create form.
     */
    public function create()
    {
        return view('essay.create');
    }

    /**
     * Student: store new essay.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:50'],
            'subject' => ['nullable', 'string', 'max:255'],
        ]);

        $this->essayService->createEssay(Auth::user(), $data);

        return redirect()->route('essay.index')
            ->with('success', 'Redação enviada com sucesso!');
    }

    /**
     * View a single essay with analyses.
     */
    public function show(Essay $essay)
    {
        $user = Auth::user();

        // Students can only see their own; professors can see all
        if ($user->role !== 'professor' && $essay->user_id !== $user->id) {
            abort(403);
        }

        $essay->load(['analyses.analyzer', 'user', 'lineComments.professor']);

        return view('essay.show', compact('essay'));
    }

    /**
     * Student: request AI analysis.
     */
    public function analyzeAI(Essay $essay)
    {
        if ($essay->user_id !== Auth::id()) {
            abort(403);
        }

        $this->essayService->analyzeWithAI($essay);

        return redirect()->route('essay.show', $essay)
            ->with('success', 'Análise por IA concluída!');
    }

    // ---- Professor routes ----

    /**
     * Professor: list all essays.
     */
    public function professorIndex()
    {
        $this->authorizeProfessor();

        $pendingEssays = $this->essayService->getSubmittedEssays();
        $allEssays = $this->essayService->getAllEssays();

        return view('essay.professor-index', compact('pendingEssays', 'allEssays'));
    }

    /**
     * Professor: show analysis form.
     */
    public function professorAnalyzeForm(Essay $essay)
    {
        $this->authorizeProfessor();

        $essay->load(['user', 'analyses', 'lineComments.professor']);

        return view('essay.professor-analyze', compact('essay'));
    }

    /**
     * Professor: submit analysis.
     */
    public function professorAnalyzeStore(Request $request, Essay $essay)
    {
        $this->authorizeProfessor();

        $data = $request->validate([
            'feedback'     => ['required', 'string', 'min:10'],
            'competency_1' => ['required', 'integer', 'min:0', 'max:200'],
            'competency_2' => ['required', 'integer', 'min:0', 'max:200'],
            'competency_3' => ['required', 'integer', 'min:0', 'max:200'],
            'competency_4' => ['required', 'integer', 'min:0', 'max:200'],
            'competency_5' => ['required', 'integer', 'min:0', 'max:200'],
        ]);

        $this->essayService->analyzeAsProfessor($essay, Auth::user(), $data);

        return redirect()->route('professor.essays')
            ->with('success', 'Correção enviada com sucesso!');
    }

    /**
     * Professor: store a line-level comment (AJAX-friendly).
     */
    public function lineCommentStore(Request $request, Essay $essay)
    {
        $this->authorizeProfessor();

        $data = $request->validate([
            'line_number' => ['required', 'integer', 'min:1', 'max:60'],
            'comment'     => ['required', 'string', 'max:1000'],
            'type'        => ['required', 'in:note,error,suggestion'],
        ]);

        $comment = EssayLineComment::create([
            'essay_id'     => $essay->id,
            'professor_id' => Auth::id(),
            'line_number'  => $data['line_number'],
            'comment'      => $data['comment'],
            'type'         => $data['type'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['id' => $comment->id, 'success' => true]);
        }

        return back()->with('success', 'Comentário adicionado.');
    }

    /**
     * Professor: delete a line comment.
     */
    public function lineCommentDestroy(Essay $essay, EssayLineComment $comment)
    {
        $this->authorizeProfessor();

        if ($comment->essay_id !== $essay->id) {
            abort(403);
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Comentário removido.');
    }

    private function authorizeProfessor(): void
    {
        if (!Auth::user()->canTeach()) {
            abort(403);
        }
    }
}
