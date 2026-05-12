<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TSCHOOL OF DEATH')</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=Special+Elite&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Special Elite', 'Courier New', monospace;
            background: linear-gradient(135deg, #0a0000 0%, #1a0000 50%, #0a0000 100%);
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
            background:
                repeating-linear-gradient(0deg, transparent 0px, transparent 40px, rgba(139,0,0,0.05) 40px, rgba(139,0,0,0.05) 80px),
                radial-gradient(circle at 20% 30%, rgba(139,0,0,0.1) 2px, transparent 2px);
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
            font-family: 'Creepster', cursive;
            font-size: 64px;
            color: #8b0000;
            text-shadow: 0 0 10px #ff0000, 0 0 20px #8b0000, 3px 3px 0 #2a0000;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            50% { text-shadow: 0 0 20px #ff0000, 0 0 40px #8b0000; }
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
            background: linear-gradient(135deg, #1a0000, #2a0000);
            color: #8b0000;
            text-decoration: none;
            font-weight: bold;
            border: 2px solid #8b0000;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
        }

        .btn-nav:hover {
            background: #3a0000;
            color: #ff3333;
            box-shadow: 0 0 20px rgba(139,0,0,0.5);
        }

        .admin-nav {
            border-color: #ff0000;
            color: #ff0000;
            animation: adminPulse 1.5s infinite;
        }

        .admin-nav:hover {
            background: #ff0000;
            color: #000000;
            box-shadow: 0 0 30px rgba(255,0,0,0.8);
        }

        @keyframes adminPulse {
            0% { text-shadow: 0 0 0px rgba(255,0,0,0); }
            50% { text-shadow: 0 0 10px rgba(255,0,0,0.5); }
            100% { text-shadow: 0 0 0px rgba(255,0,0,0); }
        }

        .card {
            background: rgba(10,0,0,0.95);
            border: 2px solid #8b0000;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translate(-3px, -3px);
            box-shadow: 10px 10px 0 rgba(139,0,0,0.3);
        }

        .card h2, .card h3 {
            color: #8b0000;
            text-shadow: 1px 1px 0 #2a0000;
            font-family: 'Creepster', cursive;
        }

        .card p {
            color: #aa4a4a;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #1a0000;
            color: #8b0000;
            text-decoration: none;
            border: 1px solid #8b0000;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background: #8b0000;
            color: #000;
            box-shadow: 0 0 10px #8b0000;
        }

        .btn-danger {
            background: #2a0000;
            color: #ff0000;
            border-color: #ff0000;
        }

        .btn-danger:hover {
            background: #ff0000;
            color: #000;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: #0a0000;
            border: 1px solid #8b0000;
            color: #8b0000;
            font-family: 'Special Elite', monospace;
        }

        input:focus {
            outline: none;
            border-color: #ff0000;
            box-shadow: 0 0 15px rgba(139,0,0,0.5);
        }

        label {
            color: #8b0000;
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
            background: #1a0000;
            border: 1px solid #8b0000;
            color: #8b0000;
            text-decoration: none;
        }

        .pagination a:hover {
            background: #8b0000;
            color: #000;
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
        <h1>@yield('header', '🔪 TSCHOOL OF DEATH 🔪')</h1>
    </div>

    <div class="nav-buttons">
        <a href="{{ route('home') }}" class="btn-nav">🏠 ДОМ КРОВИ</a>
        <a href="{{ route('events.index') }}" class="btn-nav">📅 КНИГА СМЕРТИ</a>
        <a href="{{ route('tickets.index') }}" class="btn-nav">🎫 ЖАЛОБЫ ДУШ</a>

        @auth
            @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="btn-nav admin-nav">👑 АДМИНКА</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-nav">🚪 ПОКИНУТЬ АД</button>
            </form>
        @endauth
    </div>

    @if(session('success'))
        <div style="background: #1a0000; border-left: 5px solid #8b0000; padding: 10px; margin-bottom: 20px; color: #8b0000;">
            💀 {{ session('success') }} 💀
        </div>
    @endif

    @if(session('error'))
        <div style="background: #1a0000; border-left: 5px solid #ff0000; padding: 10px; margin-bottom: 20px; color: #ff0000;">
            ⚠️ {{ session('error') }} ⚠️
        </div>
    @endif

    @if($errors->any())
        <div style="background: #1a0000; border-left: 5px solid #ff0000; padding: 10px; margin-bottom: 20px; color: #ff0000;">
            @foreach($errors->all() as $error)
                💀 {{ $error }}<br>
            @endforeach
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>
