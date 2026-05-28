@extends('layouts.app')

@section('title', 'Панель модератора')

@section('header', '🛡️ ПАНЕЛЬ МОДЕРАТОРА')

@section('content')
    <!-- Статистика репортов -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalReports }}</div>
                    <div class="stat-label">⚠️ Всего репортов</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $openReports }}</div>
                    <div class="stat-label">🟢 Открытых</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $inProgressReports }}</div>
                    <div class="stat-label">🟡 В работе</div>
                </div>
            </div>
        </div>
        <div style="flex: 1; min-width: 150px;">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-number">{{ $closedReports }}</div>
                    <div class="stat-label">⚫ Закрытых</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Топы -->
    <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #2196F3;">👤 ТОП НАРУШИТЕЛЕЙ</div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <thead>
                        <tr><th>Игрок</th><th>Репортов</th></tr>
                        </thead>
                        <tbody>
                        @foreach($topOffenders as $offender)
                            <tr><td>{{ $offender->player_name }}</td><td>{{ $offender->total }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div style="flex: 1;">
            <div class="card">
                <div class="card-header" style="background: #2196F3;">🖥️ ТОП СЕРВЕРОВ</div>
                <div class="card-body">
                    <table style="width: 100%;">
                        <thead><tr><th>Сервер</th><th>Репортов</th></tr></thead>
                        <tbody>
                        @foreach($topServers as $server)
                            <tr><td>{{ $server->server_name }}</td><td>{{ $server->total }}</td></tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- График -->
    <div class="card mb-4">
        <div class="card-header" style="background: #ff9800;">📈 ДИНАМИКА РЕПОРТОВ (30 дней)</div>
        <div class="card-body">
            <canvas id="reportsChart" height="100"></canvas>
        </div>
    </div>

    <!-- Список репортов -->
    <div class="card">
        <div class="card-header">⚠️ ВСЕ РЕПОРТЫ</div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr style="border-bottom: 2px solid #4CAF50;">
                        <th>ID</th><th>Игрок</th><th>Тема</th><th>Приоритет</th><th>Статус</th><th>Дата</th><th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->player_name }}</td>
                            <td>{{ $report->title }}</td>
                            <td>@if($report->priority == 'low') 📗 @elseif($report->priority == 'medium') 📙 @else 📕 @endif</td>
                            <td>
                                @if($report->status == 'open') 🟢 Открыт
                                @elseif($report->status == 'in_progress') 🟡 В работе
                                @else ⚫ Закрыт
                                @endif
                            </td>
                            <td>{{ $report->created_at->format('d.m.Y') }}</td>
                            <td>
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-sm">👁️</a>
                                @if($report->status != 'closed')
                                    <button type="button" class="btn btn-sm" style="background: #ff9800;" onclick="openCloseModal({{ $report->id }}, '{{ $report->player_name }}')">✅ Закрыть</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
        </div>
    </div>

    <!-- Модальное окно (как в репортах) -->
    <div id="closeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: #0a1a0f; border: 2px solid #4CAF50; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%;">
            <h3 style="color: #4CAF50;">✅ Закрыть репорт с итогом</h3>
            <p id="closeModalPlayer" style="color: #ccc; margin-bottom: 20px;"></p>
            <form method="POST" id="closeModalForm">
                @csrf
                <textarea name="resolution" rows="4" required placeholder="Решение по репорту..." style="width: 100%; padding: 10px; background: #0d1f12; border: 1px solid #2e7d32; border-radius: 8px; color: #e8f5e9;"></textarea>
                <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn" style="background: #555;" onclick="closeModal()">❌ ОТМЕНА</button>
                    <button type="submit" class="btn" style="background: #4CAF50;">✅ ЗАКРЫТЬ</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('reportsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($reportsByDay30)) !!},
                datasets: [{
                    label: 'Репорты',
                    data: {!! json_encode(array_values($reportsByDay30)) !!},
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { labels: { color: '#e8f5e9' } } },
                scales: {
                    y: { ticks: { color: '#e8f5e9' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                    x: { ticks: { color: '#e8f5e9', rotation: 45 }, grid: { color: 'rgba(255,255,255,0.1)' } }
                }
            }
        });

        function openCloseModal(reportId, playerName) {
            const modal = document.getElementById('closeModal');
            const form = document.getElementById('closeModalForm');
            document.getElementById('closeModalPlayer').innerHTML = 'Репорт на игрока: <strong>' + playerName + '</strong>';
            form.action = '/reports/' + reportId + '/close';
            modal.style.display = 'flex';
        }
        function closeModal() { document.getElementById('closeModal').style.display = 'none'; }
        window.onclick = function(event) { if (event.target === document.getElementById('closeModal')) closeModal(); }
    </script>
@endsection
