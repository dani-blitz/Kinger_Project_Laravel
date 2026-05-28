@extends('layouts.admin')

@section('title', 'Статистика репортов')

@section('content')
    <div class="card">
        <div class="card-header">⚠️ СТАТИСТИКА РЕПОРТОВ</div>
        <div class="card-body">
            <!-- Основные показатели -->
            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $totalReports }}</div><div class="stat-label">Всего репортов</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $openReports }}</div><div class="stat-label">🟢 Открытых</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $inProgressReports }}</div><div class="stat-label">🟡 В работе</div></div></div>
                <div style="flex:1"><div class="stat-card"><div class="stat-number">{{ $closedReports }}</div><div class="stat-label">⚫ Закрытых</div></div></div>
            </div>

            <!-- Топы -->
            <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px;">
                <div style="flex:1"><div class="card"><div class="card-header">👤 ТОП НАРУШИТЕЛЕЙ</div><div class="card-body">@include('admin.partials.top-table', ['items' => $topOffenders, 'nameField' => 'player_name'])</div></div></div>
                <div style="flex:1"><div class="card"><div class="card-header">🖥️ ТОП СЕРВЕРОВ</div><div class="card-body">@include('admin.partials.top-table', ['items' => $topServers, 'nameField' => 'server_name'])</div></div></div>
            </div>

            <!-- График -->
            <div class="card mb-4"><div class="card-header" style="background:#ff9800;">📈 ДИНАМИКА (30 дней)</div><div class="card-body"><canvas id="reportsChart" height="100"></canvas></div></div>

            <!-- Популярные слова -->
            <div class="card mb-4"><div class="card-header">🔤 ПОПУЛЯРНЫЕ СЛОВА</div><div class="card-body"><div style="display:flex; flex-wrap:wrap; gap:10px;">@foreach($topWords as $word=>$count)<span style="background:rgba(46,125,50,0.3); padding:8px 16px; border-radius:20px;">{{ $word }} ({{ $count }})</span>@endforeach</div></div></div>

            <!-- Дополнительная статистика -->
            <div class="card"><div class="card-header">📊 ДОПОЛНИТЕЛЬНО</div><div class="card-body"><p>🟢 Открыто: {{ $statusHistory['open'] }} | 🟡 В работе: {{ $statusHistory['in_progress'] }} | ⚫ Закрыто: {{ $statusHistory['closed'] }}</p><p>⏱️ Среднее время закрытия: {{ round($avgCloseTime, 1) }} часов</p></div></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('reportsChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($reportsByDay30)) !!},
                datasets: [{ label: 'Репорты', data: {!! json_encode(array_values($reportsByDay30)) !!}, borderColor: '#4CAF50', backgroundColor: 'rgba(76,175,80,0.1)', fill: true, tension: 0.4 }]
            },
            options: { responsive: true, plugins: { legend: { labels: { color: '#e8f5e9' } } }, scales: { y: { ticks: { color: '#e8f5e9' } }, x: { ticks: { color: '#e8f5e9', rotation: 45 } } } }
        });
    </script>
@endsection
