@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
    <!-- Основная статистика -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">👥 Всего пользователей</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $confirmedUsers ?? 0 }}</div>
                    <div class="stat-label">✅ Верифицированных</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalNews ?? 0 }}</div>
                    <div class="stat-label">📢 Новостей</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalReports ?? 0 }}</div>
                    <div class="stat-label">⚠️ Репортов</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Статистика ошибок -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header">⚠️ Ошибки</div>
                <div class="card-body">
                    <p>❌ Всего ошибок: <strong>{{ $totalFailedLogs ?? 0 }}</strong></p>
                    <p>📧 Почтовых ошибок: <strong>{{ $totalEmailErrorsCount ?? 0 }}</strong></p>
                    <p>🔢 Ошибок кода: <strong>{{ $totalCodeErrorsCount ?? 0 }}</strong></p>
                    <p>📈 Процент ошибок: <strong>{{ $errorRate ?? 0 }}%</strong></p>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header">🟢 Регистрации (верифицированные)</div>
                <div class="card-body">
                    <p>📅 За сегодня: <strong>{{ $usersToday ?? 0 }}</strong></p>
                    <p>📆 За неделю: <strong>{{ $usersWeek ?? 0 }}</strong></p>
                    <p>📆 За месяц: <strong>{{ $usersMonth ?? 0 }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Популярные слова -->
    <div class="card mb-4">
        <div class="card-header">🔤 САМЫЕ ПОПУЛЯРНЫЕ СЛОВА В РЕПОРТАХ</div>
        <div class="card-body">
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @foreach($topWords ?? [] as $word => $count)
                    <span style="background: rgba(46, 125, 50, 0.3); padding: 8px 16px; border-radius: 20px; border: 1px solid #4CAF50;">
                    {{ $word }} <strong>({{ $count }})</strong>
                </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Расширенная статистика -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #2196F3;">👤 ТОП НАРУШИТЕЛЕЙ</div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                            <th>Игрок</th>
                            <th>Количество репортов</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topOffenders ?? [] as $offender)
                            <tr>
                                <td>{{ $offender->player_name }}</td>
                                <td>{{ $offender->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #2196F3;">🖥️ ТОП СЕРВЕРОВ ПО РЕПОРТАМ</div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <thead>
                        <tr>
                            <th>Сервер</th>
                            <th>Репортов</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($topServers ?? [] as $server)
                            <tr>
                                <td>{{ $server->server_name }}</td>
                                <td>{{ $server->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #ff9800;">📈 ДИНАМИКА РЕПОРТОВ (30 дней)</div>
                <div class="card-body">
                    <canvas id="reportsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #4CAF50;">📊 ДОПОЛНИТЕЛЬНАЯ СТАТИСТИКА</div>
                <div class="card-body">
                    <p>🟢 Открыто: <strong>{{ $statusHistory['open'] ?? 0 }}</strong></p>
                    <p>🟡 В работе: <strong>{{ $statusHistory['in_progress'] ?? 0 }}</strong></p>
                    <p>⚫ Закрыто: <strong>{{ $statusHistory['closed'] ?? 0 }}</strong></p>
                    <hr>
                    <p>⏱️ Среднее время закрытия репорта: <strong>{{ round($avgCloseTime ?? 0, 1) }} часов</strong></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ошибки почты и кода -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #f44336;">📧 ОШИБКИ ПОЧТЫ</div>
                <div class="card-body">
                    <p>🔧 SMTP: <strong>{{ $smtpErrors ?? 0 }}</strong> ({{ $smtpPercent ?? 0 }}%)</p>
                    <p>❌ Connection: <strong>{{ $connectionErrors ?? 0 }}</strong> ({{ $connectionPercent ?? 0 }}%)</p>
                    <p>🔑 Auth: <strong>{{ $authErrors ?? 0 }}</strong> ({{ $authPercent ?? 0 }}%)</p>
                    <p>⏱️ Timeout: <strong>{{ $timeoutErrors ?? 0 }}</strong> ({{ $timeoutPercent ?? 0 }}%)</p>
                    <p>📧 Другие: <strong>{{ $otherEmailErrors ?? 0 }}</strong> ({{ $otherEmailPercent ?? 0 }}%)</p>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #ff9800;">🔢 ОШИБКИ КОДА</div>
                <div class="card-body">
                    <p>❌ Неверный код: <strong>{{ $invalidCodeErrors ?? 0 }}</strong> ({{ $invalidCodePercent ?? 0 }}%)</p>
                    <p>⏰ Просроченный: <strong>{{ $expiredCodeErrors ?? 0 }}</strong> ({{ $expiredCodePercent ?? 0 }}%)</p>
                    <p>📧 Неверный email: <strong>{{ $wrongEmailErrors ?? 0 }}</strong> ({{ $wrongEmailPercent ?? 0 }}%)</p>
                    <p>📝 Формат кода: <strong>{{ $formatCodeErrors ?? 0 }}</strong> ({{ $formatCodePercent ?? 0 }}%)</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('reportsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($reportsByDay30 ?? [])) !!},
                datasets: [{
                    label: 'Репорты',
                    data: {!! json_encode(array_values($reportsByDay30 ?? [])) !!},
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#e8f5e9' } }
                },
                scales: {
                    y: { ticks: { color: '#e8f5e9' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                    x: { ticks: { color: '#e8f5e9', rotation: 45 }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });
    </script>
@endsection
