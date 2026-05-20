@extends('layouts.app')

@section('title', 'Главная')

@section('header', '🎖️ SQUAD SERVER PORTAL')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h3>⚠️ СОЗДАТЬ РЕПОРТ</h3>
                <p>Заметили нарушителя? Сообщите администратору.</p>
                <a href="{{ route('reports.create') }}" class="btn">📝 СОЗДАТЬ РЕПОРТ</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h3>📢 ПОСЛЕДНИЕ НОВОСТИ</h3>
                @forelse($news as $item)
                    <p><strong>{{ $item->title }}</strong> - {{ $item->created_at->format('d.m.Y') }}</p>
                @empty
                    <p>Нет новостей</p>
                @endforelse
                <a href="{{ route('news.index') }}" class="btn">📰 ВСЕ НОВОСТИ</a>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">⚠️ ПОСЛЕДНИЕ РЕПОРТЫ</div>
        <div class="card-body">
            @forelse($reports as $report)
                <p>🔴 {{ $report->player_name }} -
                    @if($report->status == 'open')
                        🟢 Открыт
                    @elseif($report->status == 'in_progress')
                        🟡 В работе
                    @else
                        ⚫ Закрыт
                    @endif
                    - {{ $report->created_at->diffForHumans() }}
                </p>
            @empty
                <p>Нет репортов</p>
            @endforelse
        </div>
    </div>
@endsection
