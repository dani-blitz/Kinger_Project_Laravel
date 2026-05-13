@extends('layouts.app')

@section('title', 'Создать тикет')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 НОВЫЙ ТИКЕТ' : '🎫 НОВЫЙ ТИКЕТ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('tickets.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>📌 ТЕМА</label>
                <input type="text" name="title" required placeholder="Тема тикета...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📝 ОПИСАНИЕ</label>
                <textarea name="description" rows="5" placeholder="Подробное описание..."></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДЕДЛАЙН</label>
                <input type="datetime-local" name="deadline">
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ ПРИОРИТЕТ</label>
                <select name="priority">
                    <option value="low">📗 Низкий</option>
                    <option value="medium">📙 Средний</option>
                    <option value="high">📕 Высокий</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📊 СТАТУС</label>
                <select name="status">
                    <option value="open">🟢 Открыт</option>
                    <option value="in_progress">🟡 В работе</option>
                    <option value="closed">⚫ Закрыт</option>
                </select>
            </div>
            <button type="submit" class="btn">✅ СОЗДАТЬ ТИКЕТ</button>
            <a href="{{ route('tickets.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
