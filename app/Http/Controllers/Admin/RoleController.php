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
            abort(403);
        }
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя изменить свою роль');
        }

        $request->validate(['role' => 'required|in:user,moderator,admin,super_admin']);

        if ($request->role === 'super_admin') {
            $existing = User::where('role', 'super_admin')->first();
            if ($existing && $existing->id !== $user->id) {
                return back()->with('error', 'Супер-админ уже существует');
            }
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Роль {$user->name} изменена");
    }

    public function updatePermissions(Request $request, User $user)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя менять свои права');
        }

        // Список всех прав, которые могут быть изменены
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

        $submitted = $request->input('permissions', []);

        foreach ($allPermissions as $perm) {
            $value = isset($submitted[$perm]) ? 1 : 0;
            UserPermission::updateOrCreate(
                ['user_id' => $user->id, 'permission' => $perm],
                ['value' => $value]
            );
        }

        return back()->with('success', "Права {$user->name} обновлены");
    }
}
