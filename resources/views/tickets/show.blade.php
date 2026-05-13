@extends('layouts.app')

@section('title', 'Просмотр задачи')

@section('header', session('theme', 'light') == 'neon' ? '🔮 ПРОСМОТР ЗАДАЧИ' : '🎫 ПРОСМОТР ЗАДАЧИ')

@section('content')
    <div class="card">
        <div class="card-header">
            <span>📋 {{ $ticket->title }}</span>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 20px;">
                <strong>📝 Описание:</strong>
                <p>{{ $ticket->description ?: 'Нет описания' }}</p>
            </div>
            <div style="margin-bottom: 20px;">
                <strong>⚡ Приоритет:</strong>
                @if($ticket->priority == 'low')
                    <span style="color: #28a745;">📗 Низкий</span>
                @elseif($ticket->priority == 'medium')
                    <span style="color: #ffc107;">📙 Средний</span>
                @else
                    <span style="color: #dc3545;">📕 Высокий</span>
                @endif
            </div>
            <div style="margin-bottom: 20px;">
                <strong>📊 Статус:</strong>
                @if($ticket->status == 'open')
                    <span style="color: #28a745;">🟢 Открыт</span>
                @elseif($ticket->status == 'in_progress')
                    <span style="color: #ffc107;">🟡 В работе</span>
                @else
                    <span style="color: #6c757d;">⚫ Закрыт</span>
                @endif
            </div>
            <div style="margin-bottom: 20px;">
                <strong>📅 Дедлайн:</strong> {{ $ticket->deadline ? date('d.m.Y H:i', strtotime($ticket->deadline)) : 'Не указан' }}
            </div>
            <div style="margin-bottom: 20px;">
                <strong>📅 Создан:</strong> {{ $ticket->created_at->format('d.m.Y H:i') }}
            </div>
            <div style="margin-top: 20px;">
                <a href="{{ route('tickets.edit', $ticket) }}" class="btn">✏️ РЕДАКТИРОВАТЬ</a>
                <a href="{{ route('tickets.index') }}" class="btn">🔙 НАЗАД</a>
            </div>
        </div>
    </div>
@endsection
