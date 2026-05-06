@extends('layouts.app')

@section('title', 'Создать событие')

@section('header', session('theme', 'horror') == 'neon' ? '✨ СОЗДАТЬ НЕОННОЕ СОБЫТИЕ' : '🔮 ПРИЗВАТЬ НОВОЕ ЗЛО')

@section('content')
    <div class="card">
        <form method="POST" action="{{ route('events.store') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>👹 НАЗВАНИЕ</label>
                <input type="text" name="title" required placeholder="Введите название...">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📜 ОПИСАНИЕ</label>
                <textarea name="description" rows="4" placeholder="Описание..."></textarea>
            </div>
            <div style="margin-bottom: 20px;">
                <label>⏰ ДАТА НАЧАЛА</label>
                <input type="datetime-local" name="start_time" required
                       min="{{ date('Y-m-d\TH:i') }}"
                       max="{{ date('Y-m-d\TH:i', strtotime('+10 years')) }}">
            </div>
            <div style="margin-bottom: 20px;">
                <label>💀 ДАТА ОКОНЧАНИЯ</label>
                <input type="datetime-local" name="end_time" required
                       min="{{ date('Y-m-d\TH:i') }}"
                       max="{{ date('Y-m-d\TH:i', strtotime('+10 years')) }}">
            </div>
            <div style="margin-bottom: 20px;">
                <label>📍 МЕСТО</label>
                <input type="text" name="location" placeholder="Где пройдет?">
            </div>
            <button type="submit" class="btn">💾 СОХРАНИТЬ</button>
            <a href="{{ route('events.index') }}" class="btn">❌ ОТМЕНА</a>
        </form>
    </div>

    <script>
        const startTimeInput = document.querySelector('input[name="start_time"]');
        const endTimeInput = document.querySelector('input[name="end_time"]');

        startTimeInput.addEventListener('change', function() {
            if (startTimeInput.value && endTimeInput.value && endTimeInput.value <= startTimeInput.value) {
                alert('Дата окончания должна быть позже даты начала!');
                endTimeInput.value = '';
            }
            endTimeInput.min = startTimeInput.value;
        });

        endTimeInput.addEventListener('change', function() {
            if (endTimeInput.value <= startTimeInput.value) {
                alert('Дата окончания должна быть позже даты начала!');
                endTimeInput.value = '';
            }
        });
    </script>
@endsection
