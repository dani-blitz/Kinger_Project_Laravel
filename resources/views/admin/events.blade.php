@extends('layouts.admin')

@section('title', 'События')

@section('content')
    <div class="card">
        <div class="card-header">
            📅 Управление событиями
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Начало</th>
                        <th>Конец</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->start_time }}</td>
                            <td>{{ $event->end_time }}</td>
                            <td>
                                @if($event->start_time > now())
                                    🔮 Предстоит
                                @elseif($event->end_time < now())
                                    ✅ Завершено
                                @else
                                    🔥 Идёт
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.events.delete', $event->id) }}" onsubmit="return confirm('Удалить событие?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 12px;">🗑 Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Нет событий</td>
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
@endsection
