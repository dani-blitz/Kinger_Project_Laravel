@extends('layouts.admin')

@section('title', 'Логи ошибок')

@section('content')
    <style>
        .error-message {
            max-width: 300px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .error-message.expanded {
            max-width: 600px;
        }
        .error-message .short {
            display: block;
        }
        .error-message .full {
            display: none;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 12px;
            word-break: break-all;
            white-space: normal;
        }
        .error-message.expanded .short {
            display: none;
        }
        .error-message.expanded .full {
            display: block;
        }
        .toggle-btn {
            background: #2e7d32;
            color: white;
            border: none;
            padding: 3px 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 11px;
            margin-left: 10px;
        }
        .toggle-btn:hover {
            background: #1b5e20;
        }
    </style>

    <script>
        function toggleMessage(element) {
            element.classList.toggle('expanded');
        }
    </script>

    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            📧 ОШИБКИ ПОЧТЫ ({{ $emailLogs->total() }})
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Тип</th>
                        <th>Попытки</th>
                        <th>Сообщение</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($emailLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->email }}</td>
                            <td>{{ $log->error_type }}</td>
                            <td>{{ $log->attempts }}</td>
                            <td>
                                <div class="error-message" onclick="toggleMessage(this)">
                                    <span class="short">{{ Str::limit($log->error_message, 50) }} <button class="toggle-btn">▼</button></span>
                                    <span class="full">{{ $log->error_message }}</span>
                                </div>
                            </td>
                            <td>{{ $log->failed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">✅ Нет ошибок почты</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $emailLogs->links() }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            🔢 ОШИБКИ КОДА ({{ $codeLogs->total() }})
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Введён код</th>
                        <th>Тип</th>
                        <th>Сообщение</th>
                        <th>Дата</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($codeLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->email }}</td>
                            <td>{{ $log->code_entered ?? '-' }}</td>
                            <td>{{ $log->error_type }}</td>
                            <td>
                                <div class="error-message" onclick="toggleMessage(this)">
                                    <span class="short">{{ Str::limit($log->error_message, 50) }} <button class="toggle-btn">▼</button></span>
                                    <span class="full">{{ $log->error_message }}</span>
                                </div>
                            </td>
                            <td>{{ $log->failed_at }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">✅ Нет ошибок кода</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 20px;">
                {{ $codeLogs->links() }}
            </div>
        </div>
    </div>
@endsection
