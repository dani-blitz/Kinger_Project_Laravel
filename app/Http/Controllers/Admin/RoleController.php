<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id')->paginate(20);
        return view('admin.manage-roles', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Только супер-админ может изменять роли');
        }

        // Запрещаем изменять роль самому себе
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ Вы не можете изменить свою собственную роль');
        }

        $request->validate([
            'role' => 'required|in:user,moderator,admin,super_admin'
        ]);

        // Запрещаем создавать второго супер-админа
        if ($request->role === 'super_admin') {
            $existingSuperAdmin = User::where('role', 'super_admin')->first();
            if ($existingSuperAdmin && $existingSuperAdmin->id !== $user->id) {
                return redirect()->back()->with('error', '❌ Нельзя создать второго супер-админа. Супер-админ уже существует: ' . $existingSuperAdmin->name);
            }
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with('success', "✅ Роль пользователя {$user->name} изменена на {$request->role}");
    }

    public function updatePermissions(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Только супер-админ может изменять права');
        }

        // Запрещаем изменять права самому себе
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ Вы не можете изменять свои собственные права');
        }

        $permissions = $request->input('permissions', []);

        // Удаляем старые права
        UserPermission::where('user_id', $user->id)->delete();

        // Добавляем новые
        foreach ($permissions as $permission => $value) {
            if ($value == '1' || $value === true) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission' => $permission,
                    'value' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', "✅ Права пользователя {$user->name} обновлены");
    }
}
