@extends('layouts.app')

@section('title', 'Добавить новость')

@section('header', '📢 ДОБАВИТЬ НОВОСТЬ')

@section('content')
    <div class="card">
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
            <button type="submit" class="btn">✅ ДОБАВИТЬ</button>
            <a href="{{ route('news.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
