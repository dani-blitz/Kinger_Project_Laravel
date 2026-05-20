@extends('layouts.app')

@section('title', 'Создать репорт')

@section('header', '⚠️ НОВЫЙ РЕПОРТ')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('reports.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>👤 ИГРОК-НАРУШИТЕЛЬ</label>
                <input type="text" name="player_name" required placeholder="Ник нарушителя">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🆔 STEAM ID (если известен)</label>
                <input type="text" name="player_steam_id" placeholder="7656119...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>🎮 НАЗВАНИЕ СЕРВЕРА</label>
                <input type="text" name="server_name" placeholder="SQUAD RUS #1">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📌 ТЕМА РЕПОРТА</label>
                <input type="text" name="title" required placeholder="Например: Подозрение на читы">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📝 ОПИСАНИЕ НАРУШЕНИЯ</label>
                <textarea name="description" rows="5" required placeholder="Подробно опишите ситуацию..."></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>🔗 ССЫЛКА НА ДОКАЗАТЕЛЬСТВО (скриншот/видео)</label>
                <input type="url" name="evidence_link" placeholder="https://...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>⚡ ПРИОРИТЕТ</label>
                <select name="priority">
                    <option value="low">📗 Низкий</option>
                    <option value="medium">📙 Средний</option>
                    <option value="high">📕 Высокий (читер)</option>
                </select>
            </div>
            <button type="submit" class="btn">✅ ОТПРАВИТЬ РЕПОРТ</button>
            <a href="{{ route('reports.index') }}" class="btn">🔙 НАЗАД</a>
        </form>
    </div>
@endsection
