@extends('layouts.app')

@section('title', 'Редактировать тикет #'.$ticket->id)

@section('header', session('theme', 'horror') == 'neon' ? '✏️ РЕДАКТИРОВАТЬ ТИКЕТ' : '✏️ ИЗМЕНИТЬ ГРЕХ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('tickets.update', $ticket) }}">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 20px;">
                <label>💀 ТЕМА</label>
                <input type="text" name="subject" value="{{ $ticket->subject }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>🔥 ПОДРОБНОСТИ</label>
                <textarea name="message" rows="6" required>{{ $ticket->message }}</textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ СТАТУС</label>
                <select name="status">
                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>🟡 Открыт</option>
                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>🔵 В работе</option>
                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>✅ Закрыт</option>
                </select>
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ ПРИОРИТЕТ</label>
                <select name="priority">
                    <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>🟢 Низкий</option>
                    <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>🟠 Средний</option>
                    <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>🔴 Высокий</option>
                </select>
            </div>
            <button type="submit" class="btn">💾 ОБНОВИТЬ</button>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn">❌ ОТМЕНА</a>
        </form>
    </div>
@endsection
