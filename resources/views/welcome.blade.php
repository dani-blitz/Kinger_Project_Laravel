@extends('layouts.app')

@section('title', 'SQUAD SERVER PORTAL')

@section('header', '🎖️ ДОБРО ПОЖАЛОВАТЬ')

@section('content')
    <div class="card" style="text-align: center;">
        <h2>🏆 SQUAD SERVER PORTAL</h2>
        <p style="margin: 20px 0;">Портал для управления сервером, репортами и новостями.</p>

        @guest
            <a href="{{ route('login') }}" class="btn">🔐 ВОЙТИ</a>
            <a href="{{ route('register') }}" class="btn">📝 РЕГИСТРАЦИЯ</a>
        @else
            <a href="{{ route('home') }}" class="btn">🚀 ПЕРЕЙТИ В ПАНЕЛЬ УПРАВЛЕНИЯ</a>
        @endguest
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>📢 НОВОСТИ СЕРВЕРА</h3>
                <p>Будьте в курсе всех событий и обновлений.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>⚠️ РЕПОРТЫ</h3>
                <p>Сообщайте о нарушениях и получайте обратную связь.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>🖥️ СЕРВЕРЫ</h3>
                <p>Информация о доступных игровых серверах SQUAD.</p>
            </div>
        </div>
    </div>
@endsection
