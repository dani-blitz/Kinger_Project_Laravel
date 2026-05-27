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
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="border-bottom: 2px solid #4CAF50;">
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">ID</th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">Имя</th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">Email</th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">Текущая роль</th>
                        <th style="padding: 12px; text-align: left; color: #e8f5e9;">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr style="border-bottom: 1px solid rgba(76, 175, 80, 0.2);">
                            <td style="padding: 12px; color: #ccc;">{{ $user->id }}</td>
                            <td style="padding: 12px; color: #e8f5e9;">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span style="color: #ffd700; font-size: 12px;"> (Это вы)</span>
                                @endif
                            </td>
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
                            <td style="padding: 12px;">
                                @if($user->id === auth()->id())
                                    <span style="color: #aaa; font-size: 12px;">🔒 Нельзя изменить себя</span>
                                @else
                                    <button type="button" class="btn" style="background: #2196F3; padding: 5px 15px; font-size: 12px;" onclick="togglePermissions({{ $user->id }})">
                                        🔧 Настроить права
                                    </button>
                                @endif
                            </td>
                        </tr>
                        <tr id="permissions-row-{{ $user->id }}" style="display: none; background: rgba(0,0,0,0.3);">
                            <td colspan="5" style="padding: 0;">
                                <div style="margin: 15px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 10px;">
                                    <div style="font-size: 16px; font-weight: bold; margin-bottom: 15px; color: #4CAF50;">
                                        ✨ Настройка прав для {{ $user->name }}
                                    </div>

                                    <!-- Смена роли -->
                                    <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #2e7d32;">
                                        <span style="color: #ccc; margin-right: 15px;">🎯 Сменить роль:</span>
                                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" style="display: inline;" id="role-form-{{ $user->id }}">
                                            @csrf
                                            <select name="role" onchange="document.getElementById('role-form-{{ $user->id }}').submit()" style="background: #2e7d32; color: white; padding: 5px 10px; border-radius: 5px; border: none; cursor: pointer;">
                                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>👤 Пользователь</option>
                                                <option value="moderator" {{ $user->role == 'moderator' ? 'selected' : '' }}>🛡️ Модератор</option>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>👑 Администратор</option>
                                                @php
                                                    $hasSuperAdmin = \App\Models\User::where('role', 'super_admin')->exists();
                                                @endphp
                                                @if(!$hasSuperAdmin || $user->role == 'super_admin')
                                                    <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>⭐ Супер-админ</option>
                                                @endif
                                            </select>
                                        </form>
                                    </div>

                                    <!-- Кнопки быстрой настройки прав -->
                                    <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #2e7d32;">
                                        <span style="color: #ccc; margin-right: 15px;">⚡ Быстрая настройка прав:</span>
                                        <button type="button" class="btn" style="background: #2196F3; padding: 5px 12px; font-size: 12px;" onclick="setPermissionsByRole({{ $user->id }}, 'user')">
                                            👤 Как пользователь
                                        </button>
                                        <button type="button" class="btn" style="background: #2196F3; padding: 5px 12px; font-size: 12px;" onclick="setPermissionsByRole({{ $user->id }}, 'moderator')">
                                            🛡️ Как модератор
                                        </button>
                                        <button type="button" class="btn" style="background: #2196F3; padding: 5px 12px; font-size: 12px;" onclick="setPermissionsByRole({{ $user->id }}, 'admin')">
                                            👑 Как администратор
                                        </button>
                                    </div>

                                    <form method="POST" action="{{ route('admin.users.update-permissions', $user) }}" id="permissions-form-{{ $user->id }}">
                                        @csrf
                                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                                            @php
                                                $permissions = [
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
                                            @foreach($permissions as $key => $label)
                                                @php
                                                    $hasPermission = $user->extraPermissions()->where('permission', $key)->exists();
                                                @endphp
                                                <label style="color: #ccc; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                                    <input type="checkbox" name="permissions[{{ $key }}]" value="1" class="perm-checkbox-{{ $user->id }}" data-perm="{{ $key }}" {{ $hasPermission ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                                                    {{ $label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <div style="margin-top: 20px; display: flex; gap: 10px;">
                                            <button type="submit" class="btn" style="background: #4CAF50;">💾 СОХРАНИТЬ ПРАВА</button>
                                            <button type="button" class="btn" style="background: #555;" onclick="togglePermissions({{ $user->id }})">❌ ОТМЕНА</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <script>
        const rolePermissions = {
            user: ['reports.view_own', 'news.suggest'],
            moderator: ['reports.view_all', 'reports.comment_all', 'reports.change_status', 'news.suggest', 'news.view'],
            admin: ['reports.view_all', 'reports.comment_all', 'reports.change_status', 'reports.delete',
                'news.create', 'news.publish_direct', 'news.moderate', 'news.delete',
                'users.view', 'users.edit', 'servers.view']
        };

        function setPermissionsByRole(userId, role) {
            if (!confirm('Применить стандартные права для роли "' + role + '"? Текущие индивидуальные права будут сброшены. Роль нужно будет изменить отдельно.')) {
                return;
            }

            const checkboxes = document.querySelectorAll('.perm-checkbox-' + userId);
            checkboxes.forEach(cb => {
                cb.checked = false;
            });

            const perms = rolePermissions[role] || [];
            perms.forEach(perm => {
                const checkbox = document.querySelector(`.perm-checkbox-${userId}[data-perm="${perm}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });

            const container = document.getElementById('permissions-form-' + userId);
            const btn = container.querySelector('button[type="submit"]');
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

    <style>
        tr:hover {
            background: rgba(76, 175, 80, 0.15) !important;
        }
    </style>
@endsection
