@extends('layouts.app')

@section('title', 'Редактировать событие')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 РЕДАКТИРОВАТЬ' : '✏️ РЕДАКТИРОВАТЬ СОБЫТИЕ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('events.update', $event) }}">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 20px;">
                <label>📌 НАЗВАНИЕ</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📝 ОПИСАНИЕ</label>
                <textarea name="description" rows="5">{{ old('description', $event->description) }}</textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДАТА НАЧАЛА</label>
                <input type="datetime-local" name="start_time" value="{{ date('Y-m-d\TH:i', strtotime($event->start_time)) }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📅 ДАТА ОКОНЧАНИЯ</label>
                <input type="datetime-local" name="end_time" value="{{ date('Y-m-d\TH:i', strtotime($event->end_time)) }}" required>
            </div>
            <button type="submit" class="btn">💾 СОХРАНИТЬ</button>
            <a href="{{ route('events.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
