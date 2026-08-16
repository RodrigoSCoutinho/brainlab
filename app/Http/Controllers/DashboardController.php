<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\ExamService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private ExamService $examService
    ) {}

    public function index()
    {
        $user  = Auth::user();
        $stats = $this->examService->getUserStats($user);
        $exams = $this->examService->getUserExams($user);
        $announcements = Announcement::with('author')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'exams', 'announcements'));
    }
}
