<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vortex')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
<div class="container">
    <div class="header">
        <h1>@yield('header', '🌀 VORTEX')</h1>
        <p>Управляй событиями и задачами</p>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('home') }}" class="btn-nav">🏠 ГЛАВНАЯ</a>
        <a href="{{ route('events.index') }}" class="btn-nav">📅 СОБЫТИЯ</a>
        <a href="{{ route('tickets.index') }}" class="btn-nav">🎫 ЗАДАЧИ</a>

        @auth
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="btn-nav admin-nav">👑 АДМИНКА</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn-nav">🚪 ВЫХОД</button>
            </form>
        @endauth
    </div>

    @if(session('success'))
        <div class="alert-success">✨ {{ session('success') }} ✨</div>
    @endif

    @if(session('error'))
        <div class="alert-danger">⚠️ {{ session('error') }} ⚠️</div>
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
</body>
</html>
