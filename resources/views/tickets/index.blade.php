@extends('layouts.app')

@section('title', 'Мои задачи')

@section('header', session('theme', 'light') == 'neon' ? '🔮 МОИ ЗАДАЧИ' : '🎫 МОИ ЗАДАЧИ')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>📋 Список задач</span>
            <a href="{{ route('tickets.create') }}" class="btn">➕ СОЗДАТЬ ЗАДАЧУ</a>
        </div>
        <div class="card-body">
            @if($tickets->count() > 0)
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Тема</th>
                            <th>Описание</th>
                            <th>Статус</th>
                            <th>Приоритет</th>
                            <th>Дедлайн</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ Str::limit($ticket->description, 50) }}</td>
                                <td>
                                    @if($ticket->status == 'open')
                                        <span style="color: #28a745;">🟢 Открыт</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span style="color: #ffc107;">🟡 В работе</span>
                                    @else
                                        <span style="color: #6c757d;">⚫ Закрыт</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->priority == 'low')
                                        <span style="color: #28a745;">📗 Низкий</span>
                                    @elseif($ticket->priority == 'medium')
                                        <span style="color: #ffc107;">📙 Средний</span>
                                    @else
                                        <span style="color: #dc3545;">📕 Высокий</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->deadline ? date('d.m.Y H:i', strtotime($ticket->deadline)) : '-' }}</td>
                                <td>
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm">✏️</a>
                                    <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить задачу?')">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $tickets->links() }}
                </div>
            @else
                <p style="text-align: center;">У вас пока нет задач. <a href="{{ route('tickets.create') }}">Создать первую задачу</a></p>
            @endif
        </div>
    </div>
@endsection
