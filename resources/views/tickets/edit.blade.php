@extends('layouts.app')

@section('title', 'Редактировать тикет')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 РЕДАКТИРОВАТЬ' : '✏️ РЕДАКТИРОВАТЬ ТИКЕТ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 20px;">
                <label>📌 ТЕМА</label>
                <input type="text" name="title" value="{{ old('title', $ticket->title) }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📝 ОПИСАНИЕ</label>
                <textarea name="description" rows="5">{{ old('description', $ticket->description) }}</textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДЕДЛАЙН</label>
                <input type="datetime-local" name="deadline" value="{{ $ticket->deadline ? date('Y-m-d\TH:i', strtotime($ticket->deadline)) : '' }}">
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ ПРИОРИТЕТ</label>
                <select name="priority">
                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>📗 Низкий</option>
                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>📙 Средний</option>
                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>📕 Высокий</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📊 СТАТУС</label>
                <select name="status">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>🟢 Открыт</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🟡 В работе</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>⚫ Закрыт</option>
                </select>
            </div>
            <button type="submit" class="btn">💾 СОХРАНИТЬ</button>
            <a href="{{ route('tickets.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
