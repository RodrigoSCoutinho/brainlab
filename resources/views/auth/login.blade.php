<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — BrainLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-page">
        <div class="auth-page__card">
            <div class="auth-page__logo">
                <h1>🧠 BrainLab</h1>
                <p>Prepare-se para o IFRN</p>
            </div>

            @if($errors->any())
                <div class="feedback feedback--wrong" style="margin-bottom: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Sua senha" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Lembrar de mim</label>
                </div>

                <button type="submit" class="btn btn--primary btn--block btn--lg">Entrar</button>
            </form>

            <div class="auth-page__footer">
                Não tem conta? <a href="{{ route('register') }}">Cadastre-se</a>
            </div>
        </div>
    </div>
</body>
</html>
