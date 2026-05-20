@extends('layouts.admin')

@section('title', 'Модерация новостей')

@section('content')
    <div class="card">
        <div class="card-header">
            📢 НОВОСТИ НА МОДЕРАЦИИ
        </div>
        <div class="card-body">
            @forelse($pendingNews as $news)
                <div class="card mb-3" style="background: rgba(0,0,0,0.2);">
                    <div class="card-body">
                        <h3>{{ $news->title }}</h3>
                        <p>{{ $news->description }}</p>
                        <p><small>📅 {{ $news->created_at->format('d.m.Y H:i') }}</small></p>
                        <p><small>👤 Автор: {{ $news->user->name ?? 'Неизвестен' }}</small></p>

                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <form method="POST" action="{{ route('admin.news.approve', $news->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn" style="background: #4CAF50;">✅ ОДОБРИТЬ</button>
                            </form>

                            <form method="POST" action="{{ route('admin.news.reject', $news->id) }}" style="display: inline;" onsubmit="return confirm('Указать причину отказа?')">
                                @csrf
                                <input type="text" name="comment" placeholder="Причина отказа" style="display: inline-block; width: 200px;">
                                <button type="submit" class="btn btn-danger">❌ ОТКЛОНИТЬ</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p>Нет новостей на модерации</p>
            @endforelse
        </div>
    </div>
@endsection
