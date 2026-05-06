@extends('layouts.app')

@section('title', 'События')

@section('header', session('theme', 'horror') == 'neon' ? '📅 НЕОННЫЕ СОБЫТИЯ' : '📖 КНИГА ПРОКЛЯТЫХ СОБЫТИЙ')

@section('content')
    <div class="nav-buttons">
        <a href="{{ route('events.create') }}" class="btn-nav">➕ СОЗДАТЬ СОБЫТИЕ</a>
    </div>

    @forelse($events as $event)
        @php
            $now = now();
            $end = \Carbon\Carbon::parse($event->end_time);
            if ($end < $now) {
                $status = 'expired';
                $statusText = 'Завершено';
            } elseif ($end >= $now) {
                $status = 'current';
                $statusText = 'Актуально';
            } else {
                $status = 'upcoming';
                $statusText = 'Предстоит';
            }
        @endphp

        <div class="card">
            <h3>{{ $event->title }}</h3>
            <p>{{ $event->description ?? 'Нет описания' }}</p>
            <p>📅 Начало: {{ \Carbon\Carbon::parse($event->start_time)->format('d.m.Y H:i') }}</p>
            <p>🏁 Конец: {{ \Carbon\Carbon::parse($event->end_time)->format('d.m.Y H:i') }}</p>
            @if($event->location)
                <p>📍 {{ $event->location }}</p>
            @endif
            <p><span class="badge">{{ $statusText }}</span></p>

            <div style="margin-top: 15px;">
                <a href="{{ route('events.edit', $event) }}" class="btn">✏️ Редактировать</a>
                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить?')">🗑️ Удалить</button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <p style="text-align: center;">😈 НЕТ СОБЫТИЙ... СОЗДАЙ ПЕРВОЕ 😈</p>
        </div>
    @endforelse

    {{ $events->links() }}
@endsection
