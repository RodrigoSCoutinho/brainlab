@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1><i class="fa-solid fa-users-gear"></i> Gerenciar Usuários</h1>
            <p class="text-muted">Visualize e altere os cargos dos usuários da plataforma.</p>
        </div>
        <a href="{{ route('admin.index') }}" class="btn btn--secondary">
            <i class="fa-solid fa-arrow-left"></i> Voltar ao Admin
        </a>
    </div>

    {{-- Filters --}}
    <div class="card">
        <form method="GET" action="{{ route('admin.users') }}" class="flex gap-md" style="flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nome ou email...">
            </div>
            <div>
                <label class="form-label">Cargo</label>
                <select name="role" class="form-input">
                    <option value="">Todos</option>
                    <option value="student"   {{ request('role') === 'student'   ? 'selected' : '' }}>Estudante</option>
                    <option value="professor" {{ request('role') === 'professor' ? 'selected' : '' }}>Professor</option>
                    <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn--primary"><i class="fa-solid fa-magnifying-glass"></i> Filtrar</button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users') }}" class="btn btn--secondary">Limpar</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Users table --}}
    <div class="card">
        <div class="card__header">
            {{ $users->total() }} usuário(s) encontrado(s)
        </div>

        @if($users->isEmpty())
            <div class="empty-state">
                <div class="empty-state__icon"><i class="fa-solid fa-users-slash"></i></div>
                <div class="empty-state__text">Nenhum usuário encontrado com os filtros aplicados.</div>
            </div>
        @else
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cargo atual</th>
                        <th>Criado em</th>
                        <th>Alterar cargo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="text-muted">{{ $u->id }}</td>
                            <td>
                                {{ $u->name }}
                                @if($u->id === Auth::id())
                                    <span class="text-muted" style="font-size: 0.75rem;">(você)</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $u->email }}</td>
                            <td>
                                <span class="role-badge role-badge--{{ $u->role }}">{{ $u->roleLabel() }}</span>
                            </td>
                            <td class="text-muted">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($u->id !== Auth::id())
                                    <form action="{{ route('admin.users.role', $u) }}" method="POST" class="flex gap-sm" style="align-items: center;">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-input" style="padding: 4px 8px; font-size: 0.85rem;">
                                            <option value="student"   {{ $u->role === 'student'   ? 'selected' : '' }}>Estudante</option>
                                            <option value="professor" {{ $u->role === 'professor' ? 'selected' : '' }}>Professor</option>
                                            <option value="admin"     {{ $u->role === 'admin'     ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn--primary" style="padding: 4px 12px; font-size: 0.8rem;">
                                            Salvar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 1rem;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
