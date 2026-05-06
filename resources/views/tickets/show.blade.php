@extends('layouts.app')

@section('title', 'Просмотр тикета #'.$ticket->id)

@section('header', session('theme', 'horror') == 'neon' ? '🔍 ДЕТАЛИ ТИКЕТА' : '⚰️ ДЕТАЛИ ПРОКЛЯТИЯ')

@section('content')
    <div class="card">
        <h2>Тикет #{{ $ticket->id }}</h2>
        <h3>{{ $ticket->subject }}</h3>
        <div style="margin: 15px 0; padding: 15px; background: rgba(0,0,0,0.3); border-radius: 10px;">
            <p>{{ $ticket->message }}</p>
        </div>
        <p>📅 Создан: {{ $ticket->created_at->format('d.m.Y H:i') }}</p>
        <p>👤 Автор: {{ $ticket->user->name ?? 'Аноним' }}</p>

        <div style="margin-top: 20px;">
            <a href="{{ route('tickets.edit', $ticket) }}" class="btn">✏️ Редактировать</a>
            <a href="{{ route('tickets.index') }}" class="btn">← Назад</a>
        </div>
    </div>
@endsection
