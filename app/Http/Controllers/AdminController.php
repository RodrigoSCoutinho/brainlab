<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Essay;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'students'        => User::where('role', User::ROLE_STUDENT)->count(),
            'professors'      => User::where('role', User::ROLE_PROFESSOR)->count(),
            'admins'          => User::where('role', User::ROLE_ADMIN)->count(),
            'total_exams'     => Exam::count(),
            'total_essays'    => Essay::count(),
            'total_questions' => Question::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.index', compact('stats', 'recentUsers'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:' . implode(',', User::ROLES)],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode alterar seu próprio cargo.');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Cargo de {$user->name} atualizado para '{$user->roleLabel()}' com sucesso.");
    }
}
