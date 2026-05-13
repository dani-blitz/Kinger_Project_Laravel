@extends('layouts.app')

@section('title', 'Главная')

@section('header', '🌀 Добро пожаловать')

@section('content')
    <div class="card">
        <h2>📋 Управляй своим временем</h2>
        <p>Vortex помогает организовать события и задачи в одном месте. Планируй встречи, ставь приоритеты и отслеживай прогресс.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <h3>📅 События</h3>
                <p>Планируй встречи, дедлайны и важные даты. Все события в одном календаре.</p>
                <a href="{{ route('events.index') }}" class="btn">Перейти к событиям →</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <h3>🎫 Задачи</h3>
                <p>Управляй задачами, ставь приоритеты и статусы. Ничего не потеряй.</p>
                <a href="{{ route('tickets.index') }}" class="btn">Перейти к задачам →</a>
            </div>
        </div>
    </div>
@endsection
