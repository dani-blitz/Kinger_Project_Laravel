@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="card">
        <div class="card-header">
            👥 Управление пользователями
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Админ</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_admin)
                                    <span style="color: #10b981;">✅ Да</span>
                                @else
                                    <span style="color: #ef4444;">❌ Нет</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                @if(!$user->is_admin)
                                    <form method="POST" action="{{ route('admin.users.make-admin', $user->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn" style="padding: 5px 10px; font-size: 12px;">👑 Сделать админом</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Нет пользователей</td>
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
@endsection
