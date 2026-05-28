@extends('layouts.admin')

@section('title', 'Управление ролями')

@section('content')
    <div class="card">
        <div class="card-header">
            👑 УПРАВЛЕНИЕ РОЛЯМИ И ПРАВАМИ
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert-danger">❌ {{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Текущая роль</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }} @if($user->id === auth()->id()) <span style="color:#ffd700;">(Вы)</span> @endif</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->id === auth()->id())
                                    <span style="color:#ffd700;">
                                    @if($user->role == 'super_admin') ⭐ Супер-админ
                                        @elseif($user->role == 'admin') 👑 Администратор
                                        @elseif($user->role == 'moderator') 🛡️ Модератор
                                        @else 👤 Пользователь
                                        @endif
                                </span>
                                @else
                                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}" style="display:inline;">
                                        @csrf
                                        <select name="role" onchange="this.form.submit()" style="background:#2e7d32; color:white; padding:5px; border-radius:5px;">
                                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                            <option value="moderator" {{ $user->role == 'moderator' ? 'selected' : '' }}>Модератор</option>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                                            @php $hasSuper = \App\Models\User::where('role','super_admin')->exists(); @endphp
                                            @if(!$hasSuper || $user->role == 'super_admin')
                                                <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Супер-админ</option>
                                            @endif
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td>
                                @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-sm" onclick="togglePermissions({{ $user->id }})">🔧 Настроить права</button>
                                @else
                                    <span style="color:#aaa;">🔒 Нельзя изменить себя</span>
                                @endif
                            </td>
                        </tr>
                        <tr id="permissions-row-{{ $user->id }}" style="display:none;">
                            <td colspan="5">
                                <div style="background: rgba(0,0,0,0.2); padding:15px; border-radius:10px; margin:10px 0;">
                                    <strong>✏️ Дополнительные права для {{ $user->name }}</strong>
                                    <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}" id="form-{{ $user->id }}">
                                        @csrf
                                        <div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
                                            @php
                                                $perms = [
                                                    'reports.view_all' => '👁️ Просмотр всех репортов',
                                                    'reports.comment_all' => '💬 Комментирование всех репортов',
                                                    'reports.change_status' => '🔄 Изменение статуса репортов',
                                                    'reports.delete' => '🗑️ Удаление репортов',
                                                    'news.create' => '📝 Создание новостей',
                                                    'news.publish_direct' => '✅ Прямая публикация новостей',
                                                    'news.moderate' => '⚖️ Модерация новостей',
                                                    'news.delete' => '🗑️ Удаление новостей',
                                                    'users.view' => '👥 Просмотр пользователей',
                                                    'users.edit' => '✏️ Редактирование пользователей',
                                                    'servers.manage' => '🖥️ Управление серверами',
                                                ];
                                            @endphp
                                            @foreach($perms as $key => $label)
                                                @php
                                                    $extra = $user->extraPermissions()->where('permission', $key)->first();
                                                    $checked = $extra && $extra->value == 1;
                                                @endphp
                                                <label style="min-width:200px; cursor:pointer;">
                                                    <input type="checkbox" name="permissions[{{ $key }}]" value="1" {{ $checked ? 'checked' : '' }}>
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <div style="margin-top: 15px;">
                                            <button type="submit" class="btn btn-sm btn-success">💾 Сохранить права</button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="togglePermissions({{ $user->id }})">❌ Отмена</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>

    <script>
        function togglePermissions(userId) {
            var row = document.getElementById('permissions-row-' + userId);
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        }
    </script>
@endsection
