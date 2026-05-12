@extends('layouts.app')

@section('title', 'Подтверждение кода')

@section('header', session('theme', 'horror') == 'neon' ? '🔮 ПОДТВЕРЖДЕНИЕ' : '🔪 ПОДТВЕРДИТЕ КОД')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="font-size: 1.5rem; font-weight: bold;">
                        🔐 ПОДТВЕРЖДЕНИЕ КОДА
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert" style="background-color: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert" style="background-color: #f44336; color: white; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        @endif

                        <div style="background-color: rgba(255, 255, 255, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <p style="margin-bottom: 10px; font-size: 16px;">
                                📧 <strong>Код отправлен на почту:</strong>
                            </p>
                            <p style="margin-bottom: 15px; font-size: 18px; color: #ff6b6b; font-weight: bold;">
                                {{ session('verification_email') }}
                            </p>
                            <p style="margin-bottom: 0; font-size: 14px; color: #ffaa00;">
                                ⏰ Код действителен в течение 10 минут
                            </p>
                        </div>

                        {{-- Форма ввода кода --}}
                        <form method="POST" action="{{ route('verification.verify') }}" id="verifyForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('verification_email') }}">

                            <div style="margin-bottom: 25px;">
                                <label style="display: block; margin-bottom: 10px; font-size: 16px; font-weight: bold;">
                                    🔢 Введите код подтверждения
                                </label>
                                <input type="text"
                                       name="code"
                                       id="code"
                                       placeholder="000000"
                                       required
                                       maxlength="6"
                                       autocomplete="off"
                                       inputmode="numeric"
                                       pattern="\d{6}"
                                       style="width: 100%; max-width: 250px; font-size: 32px; text-align: center; letter-spacing: 8px; padding: 10px; border-radius: 8px; border: 2px solid #ddd; background-color: #fff; color: #000; font-weight: bold;">
                                <div id="codeError" style="color: #ff6666; font-size: 12px; margin-top: 5px; display: none;">
                                    ⚠️ Код должен содержать только 6 цифр
                                </div>
                                <small style="display: block; margin-top: 8px; color: #888;">
                                    📌 Код состоит из 6 цифр (буквы и символы не принимаются)
                                </small>
                            </div>

                            <button type="submit" class="btn" style="width: 100%; background-color: #4CAF50; color: white; padding: 12px; font-size: 16px; font-weight: bold; border: none; border-radius: 5px; cursor: pointer;">
                                ✅ ПОДТВЕРДИТЬ РЕГИСТРАЦИЮ
                            </button>
                        </form>

                        <hr style="margin: 25px 0; border-color: #444;">

                        {{-- Форма повторной отправки --}}
                        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('verification_email') }}">
                            <p style="margin-bottom: 10px; text-align: center; font-size: 14px;">
                                Не пришёл код?
                            </p>
                            <button type="submit" class="btn" style="width: 100%; background-color: #555; color: white; padding: 10px; font-size: 14px; border: none; border-radius: 5px; cursor: pointer;">
                                🔄 ОТПРАВИТЬ КОД СНОВА
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn:hover {
            opacity: 0.9;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        #code:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }

        .alert {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            const codeError = document.getElementById('codeError');
            const verifyForm = document.getElementById('verifyForm');
            const resendForm = document.getElementById('resendForm');
            let submitLock = false;

            // Валидация при вводе
            if (codeInput) {
                codeInput.addEventListener('input', function(e) {
                    let value = this.value;

                    // Удаляем всё, кроме цифр
                    const cleaned = value.replace(/[^\d]/g, '');

                    // Показываем ошибку если были буквы
                    if (value !== cleaned) {
                        codeError.style.display = 'block';
                        this.style.borderColor = '#ff6666';
                    } else {
                        codeError.style.display = 'none';
                        this.style.borderColor = '#ddd';
                    }

                    // Заменяем на очищенное значение
                    this.value = cleaned;

                    // Ограничиваем 6 символами
                    if (this.value.length > 6) {
                        this.value = this.value.slice(0, 6);
                    }

                    // Визуальная обратная связь
                    if (this.value.length === 6) {
                        this.style.borderColor = '#4CAF50';
                        this.style.backgroundColor = '#f0fff0';
                    } else if (this.value.length > 0) {
                        this.style.borderColor = '#ffaa00';
                        this.style.backgroundColor = '#fffef0';
                    } else {
                        this.style.borderColor = '#ddd';
                        this.style.backgroundColor = '#fff';
                    }
                });

                // Предотвращаем вставку текста с буквами
                codeInput.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const cleaned = pastedText.replace(/[^\d]/g, '').slice(0, 6);

                    if (cleaned) {
                        this.value = cleaned;

                        // Показываем временное уведомление
                        showNotification('Только цифры были скопированы', 'warning');

                        // Триггерим событие input для обновления стилей
                        this.dispatchEvent(new Event('input'));
                    } else {
                        showNotification('Код должен содержать только цифры!', 'error');
                    }
                });

                // Валидация перед отправкой формы
                verifyForm.addEventListener('submit', function(e) {
                    const code = codeInput.value;

                    // Блокируем повторную отправку
                    if (submitLock) {
                        e.preventDefault();
                        showNotification('Подождите, запрос уже обрабатывается...', 'warning');
                        return;
                    }

                    // Проверяем что код не пустой
                    if (!code || code.length === 0) {
                        e.preventDefault();
                        showNotification('Введите код подтверждения', 'error');
                        codeInput.style.borderColor = '#ff6666';
                        return;
                    }

                    // Проверяем что код состоит из 6 цифр
                    if (!/^\d{6}$/.test(code)) {
                        e.preventDefault();
                        showNotification('Код должен состоять ровно из 6 цифр (например: 123456)', 'error');
                        codeInput.style.borderColor = '#ff6666';
                        return;
                    }

                    // Блокируем кнопки
                    submitLock = true;
                    const submitBtn = verifyForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = '⏳ ПРОВЕРКА...';
                    }
                });

                // Авто-фокус на поле ввода
                codeInput.focus();
            }

            // Защита от повторной отправки формы resend
            if (resendForm) {
                resendForm.addEventListener('submit', function(e) {
                    if (submitLock) {
                        e.preventDefault();
                        showNotification('Подождите перед повторной отправкой', 'warning');
                        return;
                    }

                    submitLock = true;
                    const resendBtn = resendForm.querySelector('button[type="submit"]');
                    if (resendBtn) {
                        resendBtn.disabled = true;
                        resendBtn.textContent = '⏳ ОТПРАВКА...';
                    }

                    // Разблокируем через 5 секунд
                    setTimeout(() => {
                        submitLock = false;
                        if (resendBtn) {
                            resendBtn.disabled = false;
                            resendBtn.textContent = '🔄 ОТПРАВИТЬ КОД СНОВА';
                        }
                    }, 5000);
                });
            }

            // Функция показа уведомлений
            function showNotification(message, type = 'error') {
                const colors = {
                    error: '#f44336',
                    warning: '#ff9800',
                    success: '#4CAF50'
                };

                const notification = document.createElement('div');
                notification.textContent = message;
                notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: ${colors[type]};
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        `;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Добавляем анимации
            const style = document.createElement('style');
            style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
            document.head.appendChild(style);
        });
    </script>
@endsection
