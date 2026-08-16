<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Senha atual incorreta.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    public function updateOpenAI(Request $request)
    {
        if (!Auth::user()->canTeach()) {
            abort(403);
        }

        $request->validate([
            'openai_key'   => ['nullable', 'string', 'regex:/^sk-/'],
            'openai_model' => ['required', 'in:gpt-4o-mini,gpt-4o,gpt-4-turbo,gpt-3.5-turbo'],
        ]);

        $envPath = base_path('.env');
        $env     = file_get_contents($envPath);

        // Update or append OPENAI_API_KEY
        if ($request->filled('openai_key')) {
            $key = $request->input('openai_key');
            if (preg_match('/^OPENAI_API_KEY=.*/m', $env)) {
                $env = preg_replace('/^OPENAI_API_KEY=.*/m', "OPENAI_API_KEY={$key}", $env);
            } else {
                $env .= "\nOPENAI_API_KEY={$key}";
            }
        }

        // Update or append OPENAI_MODEL
        $model = $request->input('openai_model');
        if (preg_match('/^OPENAI_MODEL=.*/m', $env)) {
            $env = preg_replace('/^OPENAI_MODEL=.*/m', "OPENAI_MODEL={$model}", $env);
        } else {
            $env .= "\nOPENAI_MODEL={$model}";
        }

        file_put_contents($envPath, $env);

        // Clear config cache so the new value is used immediately
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        return back()->with('success', 'Configuração de IA salva com sucesso!');
    }
}
