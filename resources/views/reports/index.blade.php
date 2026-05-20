@extends('layouts.app')

@section('title', 'Мои репорты')

@section('header', '⚠️ МОИ РЕПОРТЫ')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>📋 Список репортов</span>
            <a href="{{ route('reports.create') }}" class="btn">➕ НОВЫЙ РЕПОРТ</a>
        </div>
        <div class="card-body">
            @forelse($reports as $report)
                <div class="card mb-3">
                    <div class="card-body">
                        <h3>👤 {{ $report->player_name }}</h3>
                        <p><strong>Причина:</strong> {{ $report->title }}</p>
                        <p><strong>Описание:</strong> {{ $report->description }}</p>
                        <p><strong>Приоритет:</strong>
                            @if($report->priority == 'low') 📗 Низкий
                            @elseif($report->priority == 'medium') 📙 Средний
                            @else 📕 Высокий
                            @endif
                        </p>
                        <p><strong>Статус:</strong>
                            @if($report->status == 'open') 🟢 Открыт
                            @elseif($report->status == 'in_progress') 🟡 В работе
                            @else ⚫ Закрыт
                            @endif
                        </p>
                        <small>📅 {{ $report->created_at->format('d.m.Y H:i') }}</small>
                        <div style="margin-top: 10px;">
                            <a href="{{ route('reports.show', $report) }}" class="btn btn-sm">👁️ ПРОСМОТР</a>
                            <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Удалить репорт?')">🗑 УДАЛИТЬ</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>У вас пока нет репортов</p>
            @endforelse
            {{ $reports->links() }}
        </div>
    </div>
@endsection
