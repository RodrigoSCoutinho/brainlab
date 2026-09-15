<?php

namespace App\Http\Controllers;

use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    public function __construct(
        private GamificationService $gamificationService
    ) {}

    public function index()
    {
        $user = Auth::user();

        $xp = $this->gamificationService->getTotalXp($user);
        $subjectLevels = $this->gamificationService->getSubjectLevels($user);
        $streak = $this->gamificationService->getStreak($user);
        $dailyGoal = $this->gamificationService->getDailyGoal($user);
        $achievements = $this->gamificationService->getAchievements($user);

        return view('gamification.index', compact('xp', 'subjectLevels', 'streak', 'dailyGoal', 'achievements'));
    }
}
