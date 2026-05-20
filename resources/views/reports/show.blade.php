@extends('layouts.app')

@section('title', 'Просмотр репорта')

@section('header', '⚠️ ПРОСМОТР РЕПОРТА')

@section('content')
    <div class="card">
        <div class="card-header">
            Репорт на игрока {{ $report->player_name }}
        </div>
        <div class="card-body">
            <p><strong>👤 Игрок:</strong> {{ $report->player_name }}</p>
            <p><strong>🆔 Steam ID:</strong> {{ $report->player_steam_id ?: 'Не указан' }}</p>
            <p><strong>🎮 Сервер:</strong> {{ $report->server_name ?: 'Не указан' }}</p>
            <p><strong>📌 Тема:</strong> {{ $report->title }}</p>
            <p><strong>📝 Описание:</strong> {{ $report->description }}</p>
            <p><strong>🔗 Доказательства:</strong>
                @if($report->evidence_link)
                    <a href="{{ $report->evidence_link }}" target="_blank">Ссылка</a>
                @else
                    Не приложены
                @endif
            </p>
            <p><strong>⚡ Приоритет:</strong>
                @if($report->priority == 'low') 📗 Низкий
                @elseif($report->priority == 'medium') 📙 Средний
                @else 📕 Высокий
                @endif
            </p>
            <p><strong>📊 Статус:</strong>
                @if($report->status == 'open') 🟢 Открыт
                @elseif($report->status == 'in_progress') 🟡 В работе
                @else ⚫ Закрыт
                @endif
            </p>
            <p><strong>📅 Создан:</strong> {{ $report->created_at->format('d.m.Y H:i') }}</p>
            <a href="{{ route('reports.index') }}" class="btn">🔙 НАЗАД</a>
        </div>
    </div>

    <!-- Комментарии -->
    <div class="card mt-4">
        <div class="card-header">💬 КОММЕНТАРИИ</div>
        <div class="card-body">
            @if($report->comments && $report->comments->count() > 0)
                @foreach($report->comments as $comment)
                    <div style="background: rgba(46, 125, 50, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 10px;">
                        <strong>{{ $comment->user->name }}</strong>
                        <small>({{ $comment->created_at->format('d.m.Y H:i') }})</small>
                        @if($comment->type == 'private')
                            <span style="color: #ff9800;">🔒 Приватно (только для админов)</span>
                        @endif
                        <p style="margin-top: 8px;">{{ $comment->comment }}</p>
                    </div>
                @endforeach
            @else
                <p>Нет комментариев</p>
            @endif

            @auth
                <form method="POST" action="{{ route('reports.comment', $report) }}" style="margin-top: 15px;">
                    @csrf
                    <textarea name="comment" rows="3" class="form-control" placeholder="Написать комментарий..." style="width: 100%; padding: 10px; background: rgba(20, 30, 25, 0.9); border: 1px solid #2e7d32; border-radius: 10px; color: #e8f5e9;"></textarea>
                    <div style="margin-top: 10px;">
                        <label style="color: #a5d6a7;">
                            <input type="checkbox" name="type" value="private">
                            Приватный (только для админов)
                        </label>
                    </div>
                    <button type="submit" class="btn" style="margin-top: 10px;">✉️ ОТПРАВИТЬ</button>
                </form>
            @endauth
        </div>
    </div>
@endsection
