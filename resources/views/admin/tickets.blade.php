@extends('layouts.admin')

@section('title', 'Задачи')

@section('content')
    <div class="card">
        <div class="card-header">
            🎫 Управление задачами
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Приоритет</th>
                        <th>Автор</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>
                                @if($ticket->status == 'open')
                                    <span style="color: #10b981;">🟢 Открыт</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span style="color: #f59e0b;">🟡 В работе</span>
                                @else
                                    <span style="color: #6b7280;">⚫ Закрыт</span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->priority == 'low')
                                    📗 Низкий
                                @elseif($ticket->priority == 'medium')
                                    📙 Средний
                                @else
                                    📕 Высокий
                                @endif
                            </td>
                            <td>{{ $ticket->user->name ?? 'Неизвестно' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.tickets.delete', $ticket->id) }}" onsubmit="return confirm('Удалить задачу?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">🗑 Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Нет задач</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
