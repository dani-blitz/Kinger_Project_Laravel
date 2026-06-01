@extends('layouts.admin')

@section('title', 'Управление ролями')

@section('content')
    <div class="card">
        <div class="card-header">
            👑 УПРАВЛЕНИЕ РОЛЯМИ И ПРАВАМИ
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert-danger" style="margin-bottom: 20px;">❌ {{ session('error') }}</div>
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

                                    <!-- Кнопки быстрой настройки -->
                                    <div style="margin: 10px 0 15px;">
                                        <span style="color:#ccc;">⚡ Быстрая настройка:</span>
                                        <button type="button" class="btn btn-sm" style="background:#2196F3; margin-left:10px;" onclick="setPermissionsByRole({{ $user->id }}, 'user')">👤 Как пользователь</button>
                                        <button type="button" class="btn btn-sm" style="background:#2196F3;" onclick="setPermissionsByRole({{ $user->id }}, 'moderator')">🛡️ Как модератор</button>
                                        <button type="button" class="btn btn-sm" style="background:#2196F3;" onclick="setPermissionsByRole({{ $user->id }}, 'admin')">👑 Как администратор</button>
                                    </div>

                                    <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}" id="form-{{ $user->id }}">
                                        @csrf
                                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 15px;">
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
                                                <label style="cursor: pointer; padding: 6px; background: rgba(0,20,0,0.4); border-radius: 12px; border-left: 3px solid #0f0; display: flex; align-items: center; gap: 8px;">
                                                    <input type="checkbox" name="permissions[{{ $key }}]" value="1" {{ $checked ? 'checked' : '' }} style="width: 18px; height: 18px;">
                                                    <span>{{ $label }}</span>
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
        // Стандартные права для каждой роли
        const rolePermissions = {
            user: ['reports.view_own', 'news.suggest'],
            moderator: ['reports.view_all', 'reports.comment_all', 'reports.change_status', 'news.suggest', 'news.view'],
            admin: ['reports.view_all', 'reports.comment_all', 'reports.change_status', 'reports.delete',
                'news.create', 'news.publish_direct', 'news.moderate', 'news.delete',
                'users.view', 'users.edit', 'servers.view']
        };

        function setPermissionsByRole(userId, role) {
            // Снимаем все галочки
            const checkboxes = document.querySelectorAll('#form-' + userId + ' input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });

            // Ставим галочки в соответствии с ролью
            const perms = rolePermissions[role] || [];
            perms.forEach(perm => {
                const checkbox = document.querySelector(`#form-${userId} input[data-perm="${perm}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });

            // Визуальный фидбек
            const btn = document.querySelector(`#form-${userId} button[type="submit"]`);
            const originalText = btn.innerHTML;
            btn.innerHTML = '⭐ ПРИМЕНИТЬ ПРЕДУСТАНОВЛЕННЫЕ ПРАВА';
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 2000);
        }

        function togglePermissions(userId) {
            const row = document.getElementById('permissions-row-' + userId);
            if (row.style.display === 'none' || row.style.display === '') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        }
    </script>
@endsection
