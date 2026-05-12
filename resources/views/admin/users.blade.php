@extends('layouts.admin')

@section('title', 'Управление пользователями')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 ПОЛЬЗОВАТЕЛИ' : '👥 УПРАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯМИ')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>👥 Все пользователи</h2>
                <span class="badge" style="background: #4CAF50; padding: 5px 10px; border-radius: 5px;">
                Всего: {{ $users->total() }}
            </span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">ID</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Имя</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Email</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Админ</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Дата регистрации</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $user->id }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $user->name }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $user->email }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    @if($user->is_admin)
                                        <span style="color: #4CAF50;">✅ Да</span>
                                    @else
                                        <span style="color: #ff6666;">❌ Нет</span>
                                    @endif
                                </td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    @if(!$user->is_admin)
                                        <form method="POST" action="{{ route('admin.users.make-admin', $user->id) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="background: #4CAF50; color: white; padding: 5px 10px;">Сделать админом</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 10px; text-align: center;">Нет пользователей</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
