@extends('layouts.admin')

@section('title', 'Логи ошибок')

@section('content')
    <script>
        function toggleMessage(element) {
            element.classList.toggle('expanded');
        }
    </script>

    <div class="card">
        <div class="card-header">📧 ОШИБКИ ПОЧТЫ ({{ $emailLogs->total() }})</div>
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
                                    <span class="short">{{ Str::limit($log->error_message, 80) }} <button class="toggle-btn">▼</button></span>
                                    <span class="full">{{ $log->error_message }}</span>
                                </div>
                            </td>
                            <td>{{ $log->failed_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">✅ Нет ошибок почты</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $emailLogs->links() }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">🔢 ОШИБКИ КОДА ({{ $codeLogs->total() }})</div>
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
                                    <span class="short">{{ Str::limit($log->error_message, 80) }} <button class="toggle-btn">▼</button></span>
                                    <span class="full">{{ $log->error_message }}</span>
                                </div>
                            </td>
                            <td>{{ $log->failed_at }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">✅ Нет ошибок кода</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $codeLogs->links() }}
        </div>
    </div>
@endsection
