@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
    <!-- Основная статистика -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 200px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">👥 Пользователей</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalEvents ?? 0 }}</div>
                    <div class="stat-label">📅 Событий</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalTickets ?? 0 }}</div>
                    <div class="stat-label">🎫 Задач</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 200px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalFailedLogs ?? 0 }}</div>
                    <div class="stat-label">⚠️ Всего ошибок</div>
                    <small>📧 {{ $totalEmailErrors ?? 0 }} | 🔢 {{ $totalCodeErrors ?? 0 }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Общая статистика запросов -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #2196F3;">📊 ВСЕГО ЗАПРОСОВ</div>
                <div class="card-body">
                    <h3>📧 Почтовых запросов: <strong>{{ $totalRegistrationAttempts ?? 0 }}</strong></h3>
                    <p>✅ Успешных: <strong>{{ $totalUsers ?? 0 }}</strong></p>
                    <p>❌ Ошибок: <strong>{{ $totalFailedLogs ?? 0 }}</strong></p>
                    <p>📈 Процент ошибок: <strong>{{ $errorRate ?? 0 }}%</strong></p>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #4CAF50;">🔢 ВСЕГО ЗАПРОСОВ КОДА</div>
                <div class="card-body">
                    <h3>🔢 Запросов кода: <strong>{{ $totalCodeRequests ?? 0 }}</strong></h3>
                    <p>❌ Ошибок кода: <strong>{{ $totalCodeErrors ?? 0 }}</strong></p>
                    <p>📈 Процент ошибок кода: <strong>{{ $codeErrorsPercent ?? 0 }}%</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика регистраций -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header">📈 Успешные регистрации</div>
                <div class="card-body">
                    <h3 style="color: #4CAF50;">{{ $successfulRegistrations ?? 0 }} / {{ $totalRegistrationAttempts ?? 0 }}</h3>
                    <p>✅ Успех: <strong>{{ $successPercent ?? 0 }}%</strong></p>
                    <hr>
                    <p>📅 За сегодня: <strong>{{ $usersToday ?? 0 }}</strong></p>
                    <p>📆 За неделю: <strong>{{ $usersWeek ?? 0 }}</strong></p>
                    <p>📆 За месяц: <strong>{{ $usersMonth ?? 0 }}</strong></p>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #ff9800;">🔢 СТАТИСТИКА ОШИБОК КОДА</div>
                <div class="card-body">
                    <p>❌ Неверный код: <strong>{{ $invalidCodeErrors ?? 0 }}</strong> (<strong>{{ $invalidCodePercent ?? 0 }}%</strong>)</p>
                    <p>⏰ Просроченный: <strong>{{ $expiredCodeErrors ?? 0 }}</strong> (<strong>{{ $expiredCodePercent ?? 0 }}%</strong>)</p>
                    <p>📧 Неверный email: <strong>{{ $wrongEmailErrors ?? 0 }}</strong> (<strong>{{ $wrongEmailPercent ?? 0 }}%</strong>)</p>
                    <p>📝 Формат кода: <strong>{{ $formatCodeErrors ?? 0 }}</strong> (<strong>{{ $formatCodePercent ?? 0 }}%</strong>)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика ошибок почты -->
    <div class="card">
        <div class="card-header" style="background: #f44336;">📧 СТАТИСТИКА ОШИБОК ПОЧТЫ (всего: {{ $totalEmailErrors ?? 0 }})</div>
        <div class="card-body">
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1;">
                    <p>🔧 SMTP: <strong>{{ $smtpErrors ?? 0 }}</strong> (<strong>{{ $smtpPercent ?? 0 }}%</strong>)</p>
                    <p>❌ Connection: <strong>{{ $connectionErrors ?? 0 }}</strong> (<strong>{{ $connectionPercent ?? 0 }}%</strong>)</p>
                </div>
                <div style="flex: 1;">
                    <p>🔑 Auth: <strong>{{ $authErrors ?? 0 }}</strong> (<strong>{{ $authPercent ?? 0 }}%</strong>)</p>
                    <p>⏱️ Timeout: <strong>{{ $timeoutErrors ?? 0 }}</strong> (<strong>{{ $timeoutPercent ?? 0 }}%</strong>)</p>
                </div>
                <div style="flex: 1;">
                    <p>📧 Другие: <strong>{{ $otherEmailErrors ?? 0 }}</strong> (<strong>{{ $otherEmailPercent ?? 0 }}%</strong>)</p>
                </div>
            </div>
        </div>
    </div>
@endsection
