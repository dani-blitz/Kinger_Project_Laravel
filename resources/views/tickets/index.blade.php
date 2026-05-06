@extends('layouts.app')

@section('title', 'Тикеты')

@section('header', session('theme', 'horror') == 'neon' ? '🎫 НЕОННЫЕ ТИКЕТЫ' : '📜 ГРЕХОВНЫЕ ЖАЛОБЫ ДУШ')

@section('content')
    <div class="nav-buttons">
        <a href="{{ route('tickets.create') }}" class="btn-nav">➕ СОЗДАТЬ ТИКЕТ</a>
    </div>

    @forelse($tickets as $ticket)
        <div class="card">
            <h3>#{{ $ticket->id }} - {{ $ticket->subject }}</h3>
            <p>{{ Str::limit($ticket->message, 100) }}</p>
            <p>
                Статус:
                @if($ticket->status == 'open') 🟡 Открыт
                @elseif($ticket->status == 'in_progress') 🔵 В работе
                @else ✅ Закрыт
                @endif
            </p>
            <p>Приоритет:
                @if($ticket->priority == 'low') 🟢 Низкий
                @elseif($ticket->priority == 'medium') 🟠 Средний
                @else 🔴 Высокий
                @endif
            </p>
            <div style="margin-top: 15px;">
                <a href="{{ route('tickets.show', $ticket) }}" class="btn">👁️ Просмотр</a>
                <a href="{{ route('tickets.edit', $ticket) }}" class="btn">✏️ Редактировать</a>
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить?')">🗑️ Удалить</button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <p style="text-align: center;">😈 НЕТ ТИКЕТОВ... СОЗДАЙ ПЕРВЫЙ 😈</p>
        </div>
    @endforelse

    {{ $tickets->links() }}
@endsection
