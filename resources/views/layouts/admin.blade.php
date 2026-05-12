<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TSCHOOL OF DEATH - @yield('title', 'Admin Panel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Admin Theme CSS (зелёная тема) -->
    <link rel="stylesheet" href="{{ asset('resources/css/admin-theme.css') }}">

    @stack('styles')
</head>
<body class="admin-theme">
<div class="header" style="padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; background: rgba(27, 94, 32, 0.95); border-bottom: 3px solid #4CAF50;">
    <nav style="display: flex; gap: 20px; align-items: center;">
        <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: #E8F5E9; font-weight: bold;">👑 АДМИН-ПАНЕЛЬ</a>
        <a href="{{ route('admin.users') }}" style="text-decoration: none; color: #E8F5E9;">👥 ПОЛЬЗОВАТЕЛИ</a>
        <a href="{{ route('admin.events') }}" style="text-decoration: none; color: #E8F5E9;">📅 СОБЫТИЯ</a>
        <a href="{{ route('admin.tickets') }}" style="text-decoration: none; color: #E8F5E9;">🎫 ТИКЕТЫ</a>
        <a href="{{ route('admin.failed-logs') }}" style="text-decoration: none; color: #E8F5E9;">⚠️ ОШИБКИ</a>
        <a href="{{ route('home') }}" style="text-decoration: none; color: #E8F5E9;">🏠 НА САЙТ</a>
    </nav>

    <div>
        <span style="color: #E8F5E9;">{{ Auth::user()->name ?? 'Admin' }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 15px;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #ff6666; cursor: pointer;">🚪 ВЫХОД</button>
        </form>
    </div>
</div>

<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    @if(session('success'))
        <div class="alert alert-success" style="background: #4CAF50; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background: #F44336; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="background: #F44336; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
