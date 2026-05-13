@extends('layouts.admin')

@section('title', 'Логи ошибок')

@section('content')
    <div class="container">
        <!-- Ошибки почты -->
        <div class="card mb-4">
            <div class="card-header" style="background: #f44336; color: white;">
                📧 ОШИБКИ ПОЧТЫ ({{ $emailLogs->total() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Код</th>
                            <th>Тип ошибки</th>
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
                                <td>{{ $log->code }}</td>
                                <td>
                                    @if($log->error_type == 'smtp') 🔧 SMTP
                                    @elseif($log->error_type == 'connection') ❌ Connection
                                    @elseif($log->error_type == 'auth') 🔑 Auth
                                    @elseif($log->error_type == 'timeout') ⏱️ Timeout
                                    @else ⚠️ {{ $log->error_type }}
                                    @endif
                                </td>
                                <td>{{ $log->attempts }}</td>
                                <td>{{ Str::limit($log->error_message, 80) }}</td>
                                <td>{{ $log->failed_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">✅ Нет ошибок почты</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $emailLogs->links() }}
            </div>
        </div>

        <!-- Ошибки кода -->
        <div class="card">
            <div class="card-header" style="background: #ff9800; color: white;">
                🔢 ОШИБКИ КОДА ({{ $codeLogs->total() }})
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Введён код</th>
                            <th>Ожидался</th>
                            <th>Тип ошибки</th>
                            <th>IP адрес</th>
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
                                <td>{{ $log->expected_code ?? '-' }}</td>
                                <td>
                                    @if($log->error_type == 'invalid') ❌ Неверный код
                                    @elseif($log->error_type == 'expired') ⏰ Просрочен
                                    @elseif($log->error_type == 'wrong_email') 📧 Неверный email
                                    @elseif($log->error_type == 'format') 📝 Формат
                                    @else ⚠️ {{ $log->error_type }}
                                    @endif
                                </td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                                <td>{{ Str::limit($log->error_message, 80) }}</td>
                                <td>{{ $log->failed_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">✅ Нет ошибок кода</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $codeLogs->links() }}
            </div>
        </div>
    </div>
@endsection
