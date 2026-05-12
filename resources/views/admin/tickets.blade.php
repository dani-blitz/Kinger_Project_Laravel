@extends('layouts.admin')

@section('title', 'Управление тикетами')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 ТИКЕТЫ' : '🎫 УПРАВЛЕНИЕ ТИКЕТАМИ')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>🎫 Все тикеты</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">ID</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Название</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Статус</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Приоритет</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Автор</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $ticket->id }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $ticket->title }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    @if($ticket->status == 'open')
                                        <span style="color: #4CAF50;">🟢 Открыт</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span style="color: #ff9800;">🟡 В работе</span>
                                    @else
                                        <span style="color: #666;">⚫ Закрыт</span>
                                    @endif
                                </td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    @if($ticket->priority == 'low')
                                        📗 Низкий
                                    @elseif($ticket->priority == 'medium')
                                        📙 Средний
                                    @else
                                        📕 Высокий
                                    @endif
                                </td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $ticket->user->name ?? 'Неизвестно' }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <form method="POST" action="{{ route('admin.tickets.delete', $ticket->id) }}" onsubmit="return confirm('Удалить тикет?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">🗑 Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 10px; text-align: center;">Нет тикетов</td>
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
    </div>
@endsection
