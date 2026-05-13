@extends('layouts.admin')

@section('title', 'Логи ошибок')

@section('content')
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            📧 Ошибки почты
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table>
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
                            <td>{{ Str::limit($log->error_message, 60) }}</td>
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
            🔢 Ошибки кода
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table>
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
                            <td>{{ Str::limit($log->error_message, 60) }}</td>
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
