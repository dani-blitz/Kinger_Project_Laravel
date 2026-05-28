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

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ Вы не можете изменить свою собственную роль');
        }

        $request->validate([
            'role' => 'required|in:user,moderator,admin,super_admin'
        ]);

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

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', '❌ Вы не можете изменять свои собственные права');
        }

        // Все возможные права (список должен совпадать с чекбоксами в представлении)
        $allPermissions = [
            'reports.view_all',
            'reports.comment_all',
            'reports.change_status',
            'reports.delete',
            'news.create',
            'news.publish_direct',
            'news.moderate',
            'news.delete',
            'users.view',
            'users.edit',
            'servers.manage',
        ];

        $submittedPermissions = $request->input('permissions', []);

        foreach ($allPermissions as $perm) {
            $value = isset($submittedPermissions[$perm]) ? true : false;
            UserPermission::updateOrCreate(
                ['user_id' => $user->id, 'permission' => $perm],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', "✅ Права пользователя {$user->name} обновлены");
    }
}
