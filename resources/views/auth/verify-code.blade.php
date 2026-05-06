@extends('layouts.app')

@section('title', 'Подтверждение')

@section('header', session('theme', 'horror') == 'neon' ? '✨ ПОДТВЕРЖДЕНИЕ' : '🔮 ПОДТВЕРДИТЕ ДУШУ')

@section('content')
    <div class="card">
        @if(session('success'))
            <div style="background: rgba(0,255,0,0.2); border-left: 4px solid #00ff00; padding: 10px; margin-bottom: 20px; color: #00ff00;">
                😈 {{ session('success') }} 😈
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(255,0,0,0.2); border-left: 4px solid #ff0000; padding: 10px; margin-bottom: 20px; color: #ff0000;">
                💀 {{ session('error') }} 💀
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf
            <div style="margin-bottom: 20px;">
                <label>🔢 ВВЕДИТЕ КОД</label>
                <input type="text" name="code" placeholder="000000" maxlength="6" required
                       style="text-align: center; font-size: 32px; letter-spacing: 10px;">
                <small style="color: #8b0000; font-size: 11px;">Код отправлен на почту {{ session('verification_email') }}</small>
            </div>
            <button type="submit" class="btn">🔮 ПОДТВЕРДИТЬ</button>
        </form>
    </div>
@endsection
