@extends('layouts.app')

@section('title', 'Vortex')

@section('header', '🌀 VORTEX')

@section('content')
    <div class="card" style="text-align: center;">
        <h2>Добро пожаловать в Vortex</h2>
        <p style="margin: 20px 0;">Современный инструмент для управления событиями и задачами.</p>

        @guest
            <a href="{{ route('login') }}" class="btn">🔐 ВОЙТИ</a>
            <a href="{{ route('register') }}" class="btn">📝 РЕГИСТРАЦИЯ</a>
        @else
            <a href="{{ route('home') }}" class="btn">🚀 ПЕРЕЙТИ В ПАНЕЛЬ</a>
        @endguest
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>📅 События</h3>
                <p>Создавай и управляй событиями</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>🎫 Задачи</h3>
                <p>Ставь приоритеты и статусы</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="text-align: center;">
                <h3>📊 Статистика</h3>
                <p>Анализируй эффективность</p>
            </div>
        </div>
    </div>
@endsection
