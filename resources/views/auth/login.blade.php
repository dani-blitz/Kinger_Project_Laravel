@extends('layouts.app')

@section('title', 'Вход')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 ВХОД' : '🔪 ВХОД В АД')

    @section('content')
        <div class="card">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label>📧 EMAIL</label>
                    <input type="email" name="email" required placeholder="Email...">
                </div>
                <div style="margin-bottom: 20px;">
                    <label>🗝️ ПАРОЛЬ</label>
                    <input type="password" name="password" required placeholder="Пароль...">
                </div>
                <button type="submit" class="btn">🔥 ВОЙТИ</button>
            </form>
            <div class="links" style="margin-top: 20px; text-align: center;">
                <a href="{{ route('register') }}" class="btn">😈 НЕТ АККАУНТА? ЗАРЕГИСТРИРУЙСЯ</a>
            </div>
        </div>
    @endsection
