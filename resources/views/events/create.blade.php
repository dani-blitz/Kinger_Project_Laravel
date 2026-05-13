@extends('layouts.app')

@section('title', 'Создать событие')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 НОВОЕ СОБЫТИЕ' : '📅 НОВОЕ СОБЫТИЕ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('events.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>📌 НАЗВАНИЕ</label>
                <input type="text" name="title" required placeholder="Название события...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📝 ОПИСАНИЕ</label>
                <textarea name="description" rows="5" placeholder="Описание события..."></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДАТА НАЧАЛА</label>
                <input type="datetime-local" name="start_time" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДАТА ОКОНЧАНИЯ</label>
                <input type="datetime-local" name="end_time" required>
            </div>
            <button type="submit" class="btn">✅ СОЗДАТЬ</button>
            <a href="{{ route('events.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
