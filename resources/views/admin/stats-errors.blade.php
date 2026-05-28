@extends('layouts.admin')

@section('title', 'Статистика ошибок')

@section('content')
    <div class="card">
        <div class="card-header">⚠️ СТАТИСТИКА ОШИБОК</div>
        <div class="card-body">
            <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $totalFailedLogs }}</div><div class="stat-label">Всего ошибок</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $totalEmailErrorsCount }}</div><div class="stat-label">📧 Почтовых</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $totalCodeErrorsCount }}</div><div class="stat-label">🔢 Кода</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $errorRate }}%</div><div class="stat-label">Процент ошибок</div></div></div>
            </div>

            <div style="display:flex; gap:20px; flex-wrap:wrap;">
                <div style="flex:1">
                    <div class="card"><div class="card-header" style="background:#f44336;">📧 ОШИБКИ ПОЧТЫ</div><div class="card-body">
                            <p>🔧 SMTP: {{ $smtpErrors }} ({{ $smtpPercent }}%)</p>
                            <p>❌ Connection: {{ $connectionErrors }} ({{ $connectionPercent }}%)</p>
                            <p>🔑 Auth: {{ $authErrors }} ({{ $authPercent }}%)</p>
                            <p>⏱️ Timeout: {{ $timeoutErrors }} ({{ $timeoutPercent }}%)</p>
                            <p>📧 Другие: {{ $otherEmailErrors }} ({{ $otherEmailPercent }}%)</p>
                        </div></div>
                </div>
                <div style="flex:1">
                    <div class="card"><div class="card-header" style="background:#ff9800;">🔢 ОШИБКИ КОДА</div><div class="card-body">
                            <p>❌ Неверный код: {{ $invalidCodeErrors }} ({{ $invalidCodePercent }}%)</p>
                            <p>⏰ Просроченный: {{ $expiredCodeErrors }} ({{ $expiredCodePercent }}%)</p>
                            <p>📧 Неверный email: {{ $wrongEmailErrors }} ({{ $wrongEmailPercent }}%)</p>
                            <p>📝 Формат кода: {{ $formatCodeErrors }} ({{ $formatCodePercent }}%)</p>
                        </div></div>
                </div>
            </div>
        </div>
    </div>
@endsection
