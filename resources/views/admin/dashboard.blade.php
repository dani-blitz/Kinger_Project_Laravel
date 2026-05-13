@extends('layouts.admin')

@section('title', 'Админ-панель')

@section('content')
    <div class="container">
        <!-- Основная статистика -->
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h2>{{ $totalUsers }}</h2>
                        <p>👥 Пользователей</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h2>{{ $totalEvents }}</h2>
                        <p>📅 Событий</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h2>{{ $totalTickets }}</h2>
                        <p>🎫 Тикетов</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h2>{{ $totalFailedLogs }}</h2>
                        <p>⚠️ Всего ошибок</p>
                        <small>📧 Почта: {{ $totalEmailErrors }} | 🔢 Код: {{ $totalCodeErrors }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Общая статистика запросов -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: #2196F3; color: white;">
                        📊 ВСЕГО ЗАПРОСОВ
                    </div>
                    <div class="card-body">
                        <h3>📧 Почтовых запросов: <strong>{{ $totalRegistrationAttempts }}</strong></h3>
                        <p>✅ Успешных: <strong>{{ $totalUsers }}</strong></p>
                        <p>❌ Ошибок: <strong>{{ $totalFailedLogs }}</strong></p>
                        <p>📈 Процент ошибок: <strong>{{ $errorRate }}%</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: #4CAF50; color: white;">
                        🔢 ВСЕГО ЗАПРОСОВ КОДА
                    </div>
                    <div class="card-body">
                        <h3>🔢 Запросов кода: <strong>{{ $totalCodeRequests }}</strong></h3>
                        <p>❌ Ошибок кода: <strong>{{ $totalCodeErrors }}</strong></p>
                        <p>📈 Процент ошибок кода: <strong>{{ $codeErrorsPercent }}%</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика регистраций -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">📈 Успешные регистрации</div>
                    <div class="card-body">
                        <h3 style="color: #4CAF50;">{{ $successfulRegistrations }} / {{ $totalRegistrationAttempts }}</h3>
                        <p>✅ Успех: <strong>{{ $successPercent }}%</strong></p>
                        <hr>
                        <p>📅 За сегодня: <strong>{{ $usersToday }}</strong></p>
                        <p>📆 За неделю: <strong>{{ $usersWeek }}</strong></p>
                        <p>📆 За месяц: <strong>{{ $usersMonth }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background: #ff9800; color: white;">
                        🔢 СТАТИСТИКА ОШИБОК КОДА (всего: {{ $totalCodeErrors }})
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h4>❌ Неверный код</h4>
                                <p><strong>{{ $invalidCodeErrors }}</strong> ошибок</p>
                                <p><strong>{{ $invalidCodePercent }}%</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h4>⏰ Просроченный</h4>
                                <p><strong>{{ $expiredCodeErrors }}</strong> ошибок</p>
                                <p><strong>{{ $expiredCodePercent }}%</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h4>📧 Неверный email</h4>
                                <p><strong>{{ $wrongEmailErrors }}</strong> ошибок</p>
                                <p><strong>{{ $wrongEmailPercent }}%</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h4>📝 Формат кода</h4>
                                <p><strong>{{ $formatCodeErrors }}</strong> ошибок</p>
                                <p><strong>{{ $formatCodePercent }}%</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика ошибок ПОЧТЫ -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="background: #f44336; color: white;">
                        📧 СТАТИСТИКА ОШИБОК ПОЧТЫ (всего: {{ $totalEmailErrors }})
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <h4>🔧 SMTP</h4>
                                <p><strong>{{ $smtpErrors }}</strong></p>
                                <p>{{ $smtpPercent }}%</p>
                            </div>
                            <div class="col-md-2">
                                <h4>❌ Connection</h4>
                                <p><strong>{{ $connectionErrors }}</strong></p>
                                <p>{{ $connectionPercent }}%</p>
                            </div>
                            <div class="col-md-2">
                                <h4>🔑 Auth</h4>
                                <p><strong>{{ $authErrors }}</strong></p>
                                <p>{{ $authPercent }}%</p>
                            </div>
                            <div class="col-md-2">
                                <h4>⏱️ Timeout</h4>
                                <p><strong>{{ $timeoutErrors }}</strong></p>
                                <p>{{ $timeoutPercent }}%</p>
                            </div>
                            <div class="col-md-2">
                                <h4>📧 Другие</h4>
                                <p><strong>{{ $otherEmailErrors }}</strong></p>
                                <p>{{ $otherEmailPercent }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
