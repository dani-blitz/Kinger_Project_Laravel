@php
    $currentTheme = session('theme', 'horror');
@endphp

@if($currentTheme == 'neon')
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'NEON SCHOOL'))</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Orbitron', monospace;
            background: linear-gradient(135deg, #1a0033 0%, #0d0020 50%, #1a0033 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                linear-gradient(rgba(157, 0, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(157, 0, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-family: 'Orbitron', monospace;
            font-size: 56px;
            background: linear-gradient(135deg, #9d00ff, #00ffff, #9d00ff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(157, 0, 255, 0.5);
            animation: gradient 3s ease infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn-nav {
            padding: 12px 25px;
            background: rgba(157, 0, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid #9d00ff;
            border-radius: 50px;
            color: #00ffff;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 12px;
        }

        .btn-nav:hover {
            background: rgba(157, 0, 255, 0.3);
            box-shadow: 0 0 20px rgba(157, 0, 255, 0.5);
            border-color: #00ffff;
            color: #ffffff;
        }

        .admin-nav {
            border: 2px solid #ff4444;
            color: #ff4444;
            background: rgba(255, 0, 0, 0.1);
        }

        .admin-nav:hover {
            background: rgba(255, 0, 0, 0.3);
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
            border-color: #ff6666;
            color: #ff6666;
        }

        .card {
            background: rgba(26, 0, 51, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid #9d00ff;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(157, 0, 255, 0.3);
            border-color: #00ffff;
        }

        .card h2, .card h3 {
            background: linear-gradient(135deg, #9d00ff, #00ffff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 15px;
        }

        .card p {
            color: #b366ff;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(157, 0, 255, 0.2);
            color: #00ffff;
            text-decoration: none;
            border: 1px solid #9d00ff;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background: rgba(157, 0, 255, 0.5);
            box-shadow: 0 0 15px rgba(157, 0, 255, 0.5);
        }

        .btn-danger {
            background: rgba(255, 0, 100, 0.2);
            border-color: #ff0066;
            color: #ff66cc;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: rgba(26, 0, 51, 0.8);
            border: 1px solid #9d00ff;
            border-radius: 10px;
            color: #00ffff;
            font-family: 'Orbitron', monospace;
        }

        input:focus {
            outline: none;
            border-color: #00ffff;
            box-shadow: 0 0 15px rgba(157, 0, 255, 0.5);
        }

        label {
            color: #9d00ff;
            font-weight: bold;
        }

        .theme-switcher {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .theme-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid;
            cursor: pointer;
            transition: all 0.3s;
        }

        .theme-btn:hover {
            transform: scale(1.1);
        }

        .theme-horror {
            background: linear-gradient(135deg, #1a0000, #8b0000);
            border-color: #ff0000;
        }

        .theme-neon {
            background: linear-gradient(135deg, #1a0033, #9d00ff);
            border-color: #00ffff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 15px;
            background: rgba(157, 0, 255, 0.1);
            border: 1px solid #9d00ff;
            border-radius: 10px;
            color: #00ffff;
            text-decoration: none;
        }

        .pagination a:hover {
            background: rgba(157, 0, 255, 0.3);
        }
    </style>
</head>
<body>
<div class="theme-switcher">
    <a href="{{ route('theme.switch', 'horror') }}" class="theme-btn theme-horror" title="Horror Theme"></a>
    <a href="{{ route('theme.switch', 'neon') }}" class="theme-btn theme-neon" title="Neon Theme"></a>
</div>

<div class="container">
    <div class="header">
        <h1>@yield('header', '⚡ NEON SCHOOL ⚡')</h1>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('home') }}" class="btn-nav">🏠 ГЛАВНАЯ</a>
        <a href="{{ route('events.index') }}" class="btn-nav">📅 СОБЫТИЯ</a>
        <a href="{{ route('tickets.index') }}" class="btn-nav">🎫 ТИКЕТЫ</a>

        @auth
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="btn-nav admin-nav">👑 АДМИНКА</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-nav">🚪 ВЫХОД</button>
            </form>
        @endauth
    </div>

    @if(session('success'))
        <div style="background: rgba(157, 0, 255, 0.2); border-left: 4px solid #00ffff; padding: 15px; margin-bottom: 20px; color: #00ffff;">
            ✨ {{ session('success') }} ✨
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
@else
    @include('layouts.horror')
@endif
