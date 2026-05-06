@extends('layouts.app')

@section('title', 'Редактировать событие')

@section('header', session('theme', 'horror') == 'neon' ? '✏️ РЕДАКТИРОВАТЬ' : '✏️ ИЗМЕНИТЬ СУДЬБУ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('events.update', $event) }}">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 20px;">
                <label>👹 НАЗВАНИЕ</label>
                <input type="text" name="title" value="{{ $event->title }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📜 ОПИСАНИЕ</label>
                <textarea name="description" rows="4">{{ $event->description }}</textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>⏰ ДАТА НАЧАЛА</label>
                <input type="datetime-local" name="start_time" value="{{ date('Y-m-d\TH:i', strtotime($event->start_time)) }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>💀 ДАТА ОКОНЧАНИЯ</label>
                <input type="datetime-local" name="end_time" value="{{ date('Y-m-d\TH:i', strtotime($event->end_time)) }}" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label>📍 МЕСТО</label>
                <input type="text" name="location" value="{{ $event->location }}">
            </div>
            <button type="submit" class="btn">💾 ОБНОВИТЬ</button>
            <a href="{{ route('events.index') }}" class="btn">❌ ОТМЕНА</a>
        </form>
    </div>
@endsection
