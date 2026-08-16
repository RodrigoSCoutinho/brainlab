@extends('layouts.app')

@section('title', 'Configurações')

@section('content')
<div class="container" style="max-width: 700px;">
    <div class="page-header">
        <h1><i class="fa-solid fa-gear"></i> Configurações</h1>
        <p class="text-muted">Gerencie seu perfil e preferências de conta.</p>
    </div>

    {{-- Role info --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card__header">Sua conta</div>
        <div style="display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;">
            <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--color-primary); display:flex; align-items:center; justify-content:center; font-size: 1.5rem; color: #fff;">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <div style="font-size: 1.1rem; font-weight: 600;">{{ $user->name }}</div>
                <div class="text-muted" style="font-size: 0.9rem;">{{ $user->email }}</div>
                <div style="margin-top: 0.4rem;">
                    <span class="role-badge role-badge--{{ $user->role }}">{{ $user->roleLabel() }}</span>
                </div>
            </div>
        </div>
        @if($user->isStudent())
            <p class="text-muted" style="margin-top: 1rem; font-size: 0.85rem;">
                <i class="fa-solid fa-info-circle"></i>
                Para se tornar professor, entre em contato com o administrador da plataforma.
            </p>
        @endif
    </div>

    {{-- Profile form --}}
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card__header">Informações do Perfil</div>
        <form action="{{ route('settings.profile') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">Nome completo</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-input @error('name') is-invalid @enderror"
                       required>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-input @error('email') is-invalid @enderror"
                       required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="btn btn--primary">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Perfil
            </button>
        </form>
    </div>

    {{-- Password form --}}
    <div class="card">
        <div class="card__header">Alterar Senha</div>
        <form action="{{ route('settings.password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="current_password">Senha atual</label>
                <input type="password" id="current_password" name="current_password"
                       class="form-input @error('current_password') is-invalid @enderror"
                       required>
                @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Nova senha</label>
                <input type="password" id="password" name="password"
                       class="form-input @error('password') is-invalid @enderror"
                       required>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirmar nova senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-input"
                       required>
            </div>

            <button type="submit" class="btn btn--primary">
                <i class="fa-solid fa-lock"></i> Alterar Senha
            </button>
        </form>
    </div>

    @if(Auth::user()->canTeach())
    {{-- OpenAI API Key (professor/admin only) --}}
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card__header"><i class="fa-solid fa-robot"></i> Integração com IA (OpenAI)</div>
        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem;">
            Configure a chave da API do ChatGPT para habilitar análise real de redações por inteligência artificial.
            A chave é armazenada no arquivo <code>.env</code> do servidor.
        </p>
        <form action="{{ route('settings.openai') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="openai_key">Chave da API OpenAI</label>
                <input type="password" id="openai_key" name="openai_key"
                       autocomplete="new-password"
                       placeholder="{{ config('services.openai.api_key') ? 'sk-••••••••••••••••••••••••••••••••' : 'sk-...' }}"
                       class="form-input @error('openai_key') is-invalid @enderror">
                @error('openai_key')<span class="form-error">{{ $message }}</span>@enderror
                @if(config('services.openai.api_key'))
                    <p style="font-size:0.82rem; color: #2e7d32; margin-top: 4px;">
                        <i class="fa-solid fa-circle-check"></i> Chave configurada. Deixe em branco para manter.
                    </p>
                @else
                    <p style="font-size:0.82rem; color: #b45309; margin-top: 4px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Nenhuma chave configurada. A análise usará heurísticas locais.
                    </p>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label" for="openai_model">Modelo</label>
                <select id="openai_model" name="openai_model" class="form-input">
                    @foreach(['gpt-4o-mini','gpt-4o','gpt-4-turbo','gpt-3.5-turbo'] as $m)
                        <option value="{{ $m }}" {{ config('services.openai.model') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn--primary">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Configuração de IA
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
