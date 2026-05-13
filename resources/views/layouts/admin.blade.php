<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vortex - @yield('title', 'Admin Panel')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body.admin-theme {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Шапка */
        .admin-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-nav {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .admin-nav a {
            color: #2e7d32;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .admin-nav a:hover {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .admin-nav a.active {
            background: #2e7d32;
            color: white;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .admin-user span {
            color: #2e7d32;
            font-weight: 500;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #d32f2f;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: #ffebee;
        }

        /* Контейнер */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Карточки */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 24px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            color: white;
            padding: 16px 20px;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Статистика */
        .stat-card {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 42px;
            font-weight: 700;
            color: #2e7d32;
        }

        .stat-label {
            color: #666;
            margin-top: 8px;
            font-size: 14px;
        }

        /* Таблицы */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px;
            background: #f5f5f5;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #2e7d32;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        tr:hover td {
            background: #f9f9f9;
        }

        /* Кнопки */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #2e7d32, #1b5e20);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            margin: 5px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, #d32f2f, #c62828);
        }

        .btn-danger:hover {
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
        }

        /* Пагинация */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            list-style: none;
        }

        .pagination a, .pagination span {
            display: inline-block;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #2e7d32;
            text-decoration: none;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: #2e7d32;
            color: white;
            border-color: #2e7d32;
        }

        .pagination .active span {
            background: #2e7d32;
            color: white;
            border-color: #2e7d32;
        }

        /* Уведомления */
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }

        /* Адаптив */
        @media (max-width: 768px) {
            .admin-header {
                flex-direction: column;
                text-align: center;
            }
            .admin-nav {
                justify-content: center;
            }
            table, th, td {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
            .stat-number {
                font-size: 28px;
            }
        }
    </style>
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
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">🚪 ВЫХОД</button>
        </form>
    </div>
</div>

<div class="container">
    @if(session('success'))
        <div class="alert-success">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            ❌ {{ session('error') }}
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

@stack('scripts')
</body>
</html>
