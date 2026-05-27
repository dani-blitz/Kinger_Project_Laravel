@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="card">
        <div class="card-header">
            👥 СПИСОК ПОЛЬЗОВАТЕЛЕЙ
            @if(auth()->user()->canDo('users.manage_roles'))
                <span style="font-size: 12px; margin-left: 15px;">Для изменения ролей и прав перейдите в 👑 УПРАВЛЕНИЕ РОЛЯМИ</span>
            @endif
        </div>
        <div class="card-body">
            <!-- Форма поиска -->
            <form method="GET" action="{{ route('admin.users') }}" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: flex-end;">
                    <div style="flex: 1; min-width: 180px;">
                        <label style="font-size: 12px;">👤 Имя</label>
                        <input type="text" name="name" value="{{ request('name') }}" placeholder="Поиск по имени..." style="width: 100%;">
                    </div>
                    <div style="flex: 1; min-width: 180px;">
                        <label style="font-size: 12px;">📧 Email</label>
                        <input type="text" name="email" value="{{ request('email') }}" placeholder="Поиск по email..." style="width: 100%;">
                    </div>
                    <div style="flex: 1; min-width: 180px;">
                        <label style="font-size: 12px;">🎮 Steam ID</label>
                        <input type="text" name="steam_id" value="{{ request('steam_id') }}" placeholder="Поиск по Steam ID..." style="width: 100%;">
                    </div>
                    <div>
                        <button type="submit" class="btn" style="background: #2196F3;">🔍 ИСКАТЬ</button>
                        <a href="{{ route('admin.users') }}" class="btn" style="background: #555;">🔄 СБРОСИТЬ</a>
                    </div>
                </div>
            </form>

            @if(request()->anyFilled(['name', 'email', 'steam_id']))
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(33, 150, 243, 0.2); border-radius: 8px;">
                    🔍 Найдено: <strong>{{ $users->total() }}</strong> пользователей
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="border-bottom: 2px solid #4CAF50;">
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">
                            <a href="{{ route('admin.users', array_merge(request()->query(), ['sort' => 'id', 'direction' => ($sortField == 'id' && $sortDirection == 'desc') ? 'asc' : 'desc'])) }}" style="color: #e8f5e9; text-decoration: none;">
                                ID
                                @if($sortField == 'id')
                                    {!! $sortDirection == 'desc' ? '▼' : '▲' !!}
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">
                            <a href="{{ route('admin.users', array_merge(request()->query(), ['sort' => 'name', 'direction' => ($sortField == 'name' && $sortDirection == 'desc') ? 'asc' : 'desc'])) }}" style="color: #e8f5e9; text-decoration: none;">
                                Имя
                                @if($sortField == 'name')
                                    {!! $sortDirection == 'desc' ? '▼' : '▲' !!}
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">
                            <a href="{{ route('admin.users', array_merge(request()->query(), ['sort' => 'email', 'direction' => ($sortField == 'email' && $sortDirection == 'desc') ? 'asc' : 'desc'])) }}" style="color: #e8f5e9; text-decoration: none;">
                                Email
                                @if($sortField == 'email')
                                    {!! $sortDirection == 'desc' ? '▼' : '▲' !!}
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">
                            <a href="{{ route('admin.users', array_merge(request()->query(), ['sort' => 'role', 'direction' => ($sortField == 'role' && $sortDirection == 'desc') ? 'asc' : 'desc'])) }}" style="color: #e8f5e9; text-decoration: none;">
                                Роль
                                @if($sortField == 'role')
                                    {!! $sortDirection == 'desc' ? '▼' : '▲' !!}
                                @endif
                            </a>
                        </th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">Steam ID</th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">
                            <a href="{{ route('admin.users', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => ($sortField == 'created_at' && $sortDirection == 'desc') ? 'asc' : 'desc'])) }}" style="color: #e8f5e9; text-decoration: none;">
                                Дата регистрации
                                @if($sortField == 'created_at')
                                    {!! $sortDirection == 'desc' ? '▼' : '▲' !!}
                                @endif
                            </a>
                        </th>
                        @if(auth()->user()->canDo('users.edit'))
                            <th style="padding: 12px; text-align: left; color: #e8f5e9;">Действия</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid rgba(76, 175, 80, 0.2);">
                            <td style="padding: 12px; color: #ccc;">{{ $user->id }}</td>
                            <td style="padding: 12px; color: #e8f5e9;">{{ $user->name }}</td>
                            <td style="padding: 12px; color: #ccc;">{{ $user->email }}</td>
                            <td style="padding: 12px;">
                                @if($user->role == 'super_admin')
                                    <span style="color: #ffd700;">⭐ Супер-админ</span>
                                @elseif($user->role == 'admin')
                                    <span style="color: #4CAF50;">👑 Администратор</span>
                                @elseif($user->role == 'moderator')
                                    <span style="color: #2196F3;">🛡️ Модератор</span>
                                @else
                                    <span style="color: #aaa;">👤 Пользователь</span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: #ccc;">{{ $user->steam_id ?: 'Не привязан' }}</td>
                            <td style="padding: 12px; color: #ccc;">{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            @if(auth()->user()->canDo('users.edit'))
                                <td style="padding: 12px;">
                                    @if($user->id !== auth()->id() && auth()->user()->canDo('users.manage_roles'))
                                        <button type="button" class="btn btn-sm" onclick="alert('Редактирование пользователя в разработке')">
                                            ✏️ Редактировать
                                        </button>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 12px; text-align: center; color: #ccc;">Пользователи не найдены</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
