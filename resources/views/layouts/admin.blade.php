<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vortex - @yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}">
    @stack('styles')
</head>
<body class="admin-theme">
<div class="admin-header">
    <div class="admin-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 ДАШБОРД</a>
        <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">👥 ПОЛЬЗОВАТЕЛИ</a>
        <a href="{{ route('admin.events') }}" class="{{ request()->routeIs('admin.events') ? 'active' : '' }}">📅 СОБЫТИЯ</a>
        <a href="{{ route('admin.tickets') }}" class="{{ request()->routeIs('admin.tickets') ? 'active' : '' }}">🎫 ЗАДАЧИ</a>
        <a href="{{ route('admin.failed-logs') }}" class="{{ request()->routeIs('admin.failed-logs') ? 'active' : '' }}">⚠️ ОШИБКИ</a>
        <a href="{{ route('home') }}">🏠 НА САЙТ</a>
    </div>
    <div class="admin-user">
        <span>👋 {{ Auth::user()->name ?? 'Admin' }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">🚪 ВЫХОД</button>
        </form>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">❌ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-danger">
            @foreach($errors->all() as $error)
                • {{ $error }}<br>
            @endforeach
        </div>
    @endif
    @yield('content')
</div>
@stack('scripts')
</body>
</html>
