@extends('layouts.app')

@section('title', 'Главная')

@section('header', session('theme', 'horror') == 'neon' ? '✨ ДОБРО ПОЖАЛОВАТЬ ✨' : '⚰️ ДОБРО ПОЖАЛОВАТЬ В АД ⚰️')

@section('content')
    <div class="card" style="text-align: center;">
        @auth
            <h2>👹 {{ Auth::user()->name }}, ТЫ ПОПАЛ В АД!!! 👹</h2>
            <p style="margin: 20px 0;">Твоя душа теперь принадлежит нам... Выбирай своё наказание:</p>
        @else
            <h2>👹 ДОБРО ПОЖАЛОВАТЬ В ШКОЛУ СМЕРТИ 👹</h2>
            <p style="margin: 20px 0;">Чтобы продолжить, <a href="{{ route('login') }}" style="color:#8b0000;">ВОЙДИ</a> или <a href="{{ route('register') }}" style="color:#8b0000;">ЗАРЕГИСТРИРУЙСЯ</a></p>
        @endauth

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('events.index') }}" class="btn" style="padding: 15px 30px; font-size: 18px;">📖 ПРОКЛЯТЫЕ СОБЫТИЯ</a>
            <a href="{{ route('tickets.index') }}" class="btn" style="padding: 15px 30px; font-size: 18px;">📜 ГРЕХИ ТВОИ</a>
        </div>
    </div>

    <div class="card">
        <h3>🔮 ПРОРОЧЕСТВО СМЕРТИ</h3>
        <p>Твой конец наступит через {{ rand(1, 100) }} дней... Наслаждайся страхом!</p>
    </div>
@endsection
