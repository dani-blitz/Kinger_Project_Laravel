@extends('layouts.app')

@section('title', 'Подтверждение кода')

@section('header', '🔑 ПОДТВЕРЖДЕНИЕ РЕГИСТРАЦИИ')

@section('content')
    <div class="card">
        <div class="card-body">
            <p style="margin-bottom: 20px;">
                📧 <strong>Код подтверждения отправлен на почту</strong><br>
                {{ session('verification_email') }}
            </p>
            <p style="margin-bottom: 20px; color: #ff9800;">
                ⏰ Код действителен 10 минут
            </p>

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('verification_email') }}">
                <div style="margin-bottom: 20px;">
                    <label>🔢 ВВЕДИТЕ 6-ЗНАЧНЫЙ КОД</label>
                    <input type="text" name="code" placeholder="000000" required maxlength="6" style="font-size: 24px; text-align: center; letter-spacing: 5px;">
                </div>
                <button type="submit" class="btn">✅ ПОДТВЕРДИТЬ РЕГИСТРАЦИЮ</button>
            </form>

            <hr style="margin: 20px 0;">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('verification_email') }}">
                <p style="margin-bottom: 10px;">Не получили код?</p>
                <button type="submit" class="btn" style="background-color: #555;">🔄 ОТПРАВИТЬ СНОВА</button>
            </form>
        </div>
    </div>
@endsection
