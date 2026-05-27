@extends('layouts.app')

@section('title', 'Регистрация')

@section('header', '🎖️ РЕГИСТРАЦИЯ НА ПОРТАЛЕ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>👤 ВАШЕ ИМЯ</label>
                <input type="text" name="name" required placeholder="Игровой ник или реальное имя">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📧 EMAIL</label>
                <input type="email" name="email" required placeholder="Email для подтверждения">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🗝️ ПАРОЛЬ</label>
                <input type="password" name="password" required placeholder="Минимум 6 символов">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🗝️ ПОДТВЕРЖДЕНИЕ ПАРОЛЯ</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn">🔐 ЗАРЕГИСТРИРОВАТЬСЯ</button>
        </form>
        <div class="links" style="margin-top: 20px; text-align: center;">
            <a href="{{ route('login') }}" class="btn">🔙 УЖЕ ЕСТЬ АККАУНТ? ВОЙТИ</a>
        </div>
    </div>
@endsection
