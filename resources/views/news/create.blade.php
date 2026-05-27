@extends('layouts.app')

@section('title', auth()->user()->canDo('news.create') || auth()->user()->canDo('news.publish_direct') ? 'Добавить новость' : 'Предложить новость')

@section('header', auth()->user()->canDo('news.create') || auth()->user()->canDo('news.publish_direct') ? '📝 ДОБАВИТЬ НОВОСТЬ' : '📝 ПРЕДЛОЖИТЬ НОВОСТЬ')

@section('content')
    <div class="card">
        <div class="card-body">
            @if(!auth()->user()->canDo('news.publish_direct') && !auth()->user()->isAdmin())
                <div class="alert-info" style="background: #2196F3; color: white; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                    ⚡ Ваша новость будет отправлена на модерацию. После одобрения администратором она появится на сайте.
                </div>
            @endif

            <form method="POST" action="{{ route('news.store') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label>📌 ЗАГОЛОВОК</label>
                    <input type="text" name="title" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label>📝 ТЕКСТ НОВОСТИ</label>
                    <textarea name="description" rows="5" required></textarea>
                </div>
                <div style="margin-bottom: 20px;">
                    <label>📅 ДАТА НАЧАЛА</label>
                    <input type="datetime-local" name="start_time" required>
                </div>
                <div style="margin-bottom: 20px;">
                    <label>📅 ДАТА ОКОНЧАНИЯ</label>
                    <input type="datetime-local" name="end_time" required>
                </div>
                <button type="submit" class="btn">
                    @if(auth()->user()->canDo('news.create') || auth()->user()->canDo('news.publish_direct'))
                        ✅ ОПУБЛИКОВАТЬ
                    @else
                        📨 ОТПРАВИТЬ НА МОДЕРАЦИЮ
                    @endif
                </button>
                <a href="{{ route('news.index') }}" class="btn">🔙 НАЗАД</a>
            </form>
        </div>
    </div>
@endsection
