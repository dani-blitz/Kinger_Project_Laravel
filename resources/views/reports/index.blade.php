@extends('layouts.app')

@section('title', 'Репорты')

@section('header', '⚠️ РЕПОРТЫ')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <span>📋 Список репортов</span>
            @if(auth()->user()->hasRole('user'))
                <a href="{{ route('reports.create') }}" class="btn">➕ НОВЫЙ РЕПОРТ</a>
            @endif
        </div>
        <div class="card-body">
            @if($reports->count() == 0)
                <p>Нет репортов</p>
            @else
                @foreach($reports as $report)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3>👤 {{ $report->player_name }}</h3>
                                <span>
                                @if($report->priority == 'low')
                                        <span style="color: #28a745;">📗 Низкий</span>
                                    @elseif($report->priority == 'medium')
                                        <span style="color: #ffc107;">📙 Средний</span>
                                    @else
                                        <span style="color: #dc3545;">📕 Высокий</span>
                                    @endif
                            </span>
                            </div>
                            <p><strong>Тема:</strong> {{ $report->title }}</p>
                            <p><strong>Описание:</strong> {{ Str::limit($report->description, 100) }}</p>
                            <p><strong>Статус:</strong>
                                @if($report->status == 'open')
                                    <span style="color: #28a745;">🟢 Открыт</span>
                                @elseif($report->status == 'in_progress')
                                    <span style="color: #ffc107;">🟡 В работе</span>
                                @else
                                    <span style="color: #6c757d;">⚫ Закрыт</span>
                                @endif
                            </p>
                            <small>📅 {{ $report->created_at->format('d.m.Y H:i') }}</small>

                            <div style="margin-top: 15px;">
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-sm">👁️ ПРОСМОТР</a>

                                @if(auth()->user()->isModerator() && $report->status != 'closed')
                                    <button type="button" class="btn btn-sm" style="background: #ff9800;" onclick="openCloseModal({{ $report->id }}, '{{ $report->player_name }}')">
                                        ✅ Закрыть с итогом
                                    </button>
                                @endif

                                @if($report->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить репорт?')">🗑 УДАЛИТЬ</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                {{ $reports->links() }}
            @endif
        </div>
    </div>

    <!-- Модальное окно для закрытия репорта -->
    <div id="closeModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
        <div style="background: #0a1a0f; border: 2px solid #4CAF50; border-radius: 20px; padding: 30px; max-width: 500px; width: 90%;">
            <h3 style="color: #4CAF50; margin-bottom: 20px;">✅ Закрыть репорт с итогом</h3>
            <p id="closeModalPlayer" style="color: #ccc; margin-bottom: 20px;"></p>
            <form method="POST" id="closeModalForm">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="color: #e8f5e9;">📝 Итог / Решение</label>
                    <textarea name="resolution" rows="4" required placeholder="Опишите решение по репорту (наказание, предупреждение, оправдан и т.д.)" style="width: 100%; padding: 10px; background: #0d1f12; border: 1px solid #2e7d32; border-radius: 8px; color: #e8f5e9;"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn" style="background: #555;" onclick="closeModal()">❌ ОТМЕНА</button>
                    <button type="submit" class="btn" style="background: #4CAF50;">✅ ЗАКРЫТЬ РЕПОРТ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCloseModal(reportId, playerName) {
            const modal = document.getElementById('closeModal');
            const form = document.getElementById('closeModalForm');
            const playerSpan = document.getElementById('closeModalPlayer');

            playerSpan.innerHTML = 'Репорт на игрока: <strong>' + playerName + '</strong>';
            form.action = '/reports/' + reportId + '/close';
            modal.style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('closeModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('closeModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endsection
