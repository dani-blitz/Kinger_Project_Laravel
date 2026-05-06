@extends('layouts.app')

@section('title', 'Регистрация')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 РЕГИСТРАЦИЯ' : '🔮 ПРОДАТЬ ДУШУ ДЬЯВОЛУ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>👤 ИМЯ</label>
                <input type="text" name="name" required placeholder="Твоё имя...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📧 EMAIL</label>
                <input type="email" name="email" required placeholder="Email...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🗝️ ПАРОЛЬ</label>
                <input type="password" name="password" required placeholder="Пароль...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🔑 ПОДТВЕРДИТЕ ПАРОЛЬ</label>
                <input type="password" name="password_confirmation" required placeholder="Ещё раз...">
            </div>
            <button type="submit" class="btn">😈 ЗАРЕГИСТРИРОВАТЬСЯ</button>
        </form>
        <div class="links" style="margin-top: 20px; text-align: center;">
            <a href="{{ route('login') }}" class="btn">🔪 УЖЕ ЕСТЬ АККАУНТ? ВОЙТИ</a>
        </div>
    </div>
@endsection
