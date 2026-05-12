@extends('layouts.admin')

@section('title', 'Управление событиями')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 СОБЫТИЯ' : '📅 УПРАВЛЕНИЕ СОБЫТИЯМИ')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>📅 Все события</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">ID</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Название</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Начало</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Конец</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Статус</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $event->id }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $event->title }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $event->start_time }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $event->end_time }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    @if($event->start_time > now())
                                        🔮 Предстоит
                                    @elseif($event->end_time < now())
                                        ✅ Завершено
                                    @else
                                        🔥 Идёт
                                    @endif
                                </td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                                    <form method="POST" action="{{ route('admin.events.delete', $event->id) }}" onsubmit="return confirm('Удалить событие?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="background: #f44336; color: white; padding: 5px 10px;">🗑 Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 10px; text-align: center;">Нет событий</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
