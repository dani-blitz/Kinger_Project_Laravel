@extends('layouts.app')

@section('title', 'Игровые серверы')

@section('header', '🎮 ИГРОВЫЕ СЕРВЕРЫ')

@section('content')
    <div class="card">
        <div class="card-header">
            🖥️ Список серверов SQUAD
        </div>
        <div class="card-body">
            @if($servers->count() > 0)
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Название сервера</th>
                            <th>IP:Порт</th>
                            <th>Карта</th>
                            <th>Игроки</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($servers as $server)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $server->name }}</td>
                                <td>{{ $server->ip }}:{{ $server->port }}</td>
                                <td>{{ $server->map ?? '-' }}</td>
                                <td>{{ $server->players }} / {{ $server->max_players }}</td>
                                <td>
                                    @if($server->status == 'online')
                                        <span style="color: #4CAF50;">🟢 Онлайн</span>
                                    @elseif($server->status == 'maintenance')
                                        <span style="color: #ff9800;">🔧 Обслуживание</span>
                                    @else
                                        <span style="color: #f44336;">🔴 Оффлайн</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px;">
                    <h3>🖥️ Серверы скоро появятся</h3>
                    <p style="margin-top: 10px;">Список игровых серверов будет добавлен в ближайшее время.</p>
                    <p style="margin-top: 20px; color: #a5d6a7;">⚡ Ожидайте обновлений! ⚡</p>
                </div>
            @endif
        </div>
    </div>

    @auth
        @if(Auth::user()->is_admin && $servers->count() == 0)
            <div class="card mt-4">
                <div class="card-header">🔧 Для администратора</div>
                <div class="card-body">
                    <p>Чтобы добавить серверы, выполните в терминале:</p>
                    <pre style="background: #0a1a0f; padding: 15px; border-radius: 10px; overflow-x: auto;">
php artisan tinker

// Добавить тестовый сервер
App\Models\Server::create([
    'name' => 'SQUAD RUS #1',
    'ip' => '185.130.104.98',
    'port' => 7787,
    'map' => 'Yehorivka',
    'players' => 45,
    'max_players' => 100,
    'status' => 'online',
    'order' => 1
]);

// Добавить ещё один
App\Models\Server::create([
    'name' => 'SQUAD RUS #2',
    'ip' => '185.130.104.99',
    'port' => 7787,
    'map' => 'Gorodok',
    'players' => 32,
    'max_players' => 100,
    'status' => 'online',
    'order' => 2
]);

exit;
                </pre>
                </div>
            </div>
        @endif
    @endauth
@endsection
