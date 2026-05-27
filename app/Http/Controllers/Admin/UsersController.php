<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Поиск по Steam ID
        if ($request->filled('steam_id')) {
            $query->where('steam_id', 'like', '%' . $request->steam_id . '%');
        }

        // Поиск по имени
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Поиск по email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Сортировка
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'name', 'email', 'created_at', 'role'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users', compact('users', 'sortField', 'sortDirection'));
    }
}
