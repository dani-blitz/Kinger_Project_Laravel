<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vortex')</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: slideDown 0.6s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header h1 {
            font-size: 56px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 18px;
            margin-top: 10px;
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
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-nav:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .admin-nav {
            background: rgba(255, 193, 7, 0.3);
            border-color: #ffc107;
            color: #ffc107;
        }

        .admin-nav:hover {
            background: rgba(255, 193, 7, 0.5);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .card h2, .card h3 {
            color: #333;
            margin-bottom: 15px;
        }

        .card p {
            color: #666;
            line-height: 1.6;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 5px;
            font-weight: 500;
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        label {
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 8px 15px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        input[type="datetime-local"] {
            color-scheme: light;
        }

        .row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .col-md-6 {
            flex: 1;
            min-width: 250px;
        }
    </style>
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
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn-nav">🚪 ВЫХОД</button>
            </form>
        @endauth
    </div>

    @if(session('success'))
        <div class="alert-success">
            ✨ {{ session('success') }} ✨
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            ⚠️ {{ session('error') }} ⚠️
        </div>
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
