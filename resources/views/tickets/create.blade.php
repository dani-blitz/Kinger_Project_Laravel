@extends('layouts.app')

@section('title', 'Создать тикет')

@section('header', session('theme', 'horror') == 'neon' ? '✨ СОЗДАТЬ ТИКЕТ' : '📜 ПРИЗНАТЬСЯ В ГРЕХЕ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('tickets.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>💀 ТЕМА</label>
                <input type="text" name="subject" required placeholder="О чём грех?">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🔥 ПОДРОБНОСТИ</label>
                <textarea name="message" rows="6" required placeholder="Расскажи всё..."></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ ПРИОРИТЕТ</label>
                <select name="priority">
                    <option value="low">🟢 Низкий</option>
                    <option value="medium" selected>🟠 Средний</option>
                    <option value="high">🔴 Высокий</option>
                </select>
            </div>
            <button type="submit" class="btn">😈 ОТПРАВИТЬ</button>
            <a href="{{ route('tickets.index') }}" class="btn">❌ ОТМЕНА</a>
        </form>
    </div>
@endsection
