@extends('layouts.admin')

@section('title', 'Логи ошибок')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 ЛОГИ ОШИБОК' : '⚠️ ЖУРНАЛ ОШИБОК')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>⚠️ Ошибки отправки кодов</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">ID</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Email</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Код</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Попытки</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Ошибка</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Дата и время</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $log->id }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $log->email }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $log->code }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $log->attempts }}</td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee; max-width: 300px; overflow: auto;">
                                    <small style="color: #ff6666;">{{ $log->error_message }}</small>
                                </td>
                                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $log->failed_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 10px; text-align: center;">✅ Нет ошибок</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
