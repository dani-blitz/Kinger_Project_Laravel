<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Jobs\SendEmailJob;
use App\Jobs\FailedEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VerificationCodeController extends Controller
{
    public function showForm()
    {
        if (!session('verification_email')) {
            return redirect()->route('register');
        }
        return view('auth.verify');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:email_verification_codes,email',
        ]);

        $email = $request->email;

        // Генерируем новый код (только 6 цифр)
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Валидация кода: только 6 цифр
        if (!preg_match('/^\d{6}$/', $code)) {
            throw new \Exception('Сгенерирован неверный код: ' . $code);
        }

        EmailVerificationCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'is_verified' => false,
            ]
        );

        // Отправляем код
        SendEmailJob::dispatch($email, $code);

        return back()->with('success', 'Новый код отправлен на почту');
    }

    public function verifyCode(Request $request)
    {
        // Валидация: только 6 цифр, никаких букв или символов
        $request->validate([
            'email' => 'required|email',
            'code' => [
                'required',
                'string',
                'size:6',
                'regex:/^\d{6}$/', // Только цифры
            ],
        ], [
            'code.regex' => 'Код должен содержать только 6 цифр (без букв и символов)',
            'code.size' => 'Код должен состоять ровно из 6 символов',
        ]);

        // Проверяем наличие email в сессии
        if ($request->email !== session('verification_email')) {
            // Логируем попытку подмены email
            Log::warning('Попытка подмены email при верификации', [
                'session_email' => session('verification_email'),
                'submitted_email' => $request->email,
                'ip' => $request->ip()
            ]);

            // Отправляем в failed ошибку
            FailedEmailJob::dispatch(
                $request->email,
                $request->code,
                'Попытка подмены email при верификации',
                1
            );

            return back()->withErrors(['email' => 'Ошибка верификации. Попробуйте снова.']);
        }

        // Поиск кода в БД
        $verification = EmailVerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        // Код не найден или просрочен
        if (!$verification) {
            // Проверяем, существует ли код для этого email
            $exists = EmailVerificationCode::where('email', $request->email)
                ->where('code', $request->code)
                ->first();

            if ($exists && $exists->expires_at <= now()) {
                $error = 'Код просрочен. Запросите новый код.';
            } elseif ($exists) {
                $error = 'Неверный код проверки.';
            } else {
                $error = 'Неверный код. Попробуйте снова.';
            }

            // Отправляем в failed ошибку
            FailedEmailJob::dispatch(
                $request->email,
                $request->code,
                $error . ' Предоставлен код: ' . $request->code,
                1
            );

            return back()->withErrors(['code' => $error]);
        }

        // Код верный - подтверждаем
        $verification->update(['is_verified' => true]);

        // Создаём пользователя
        try {
            $user = User::create([
                'name' => session('verification_name'),
                'email' => $request->email,
                'password' => Hash::make(session('verification_password')),
                'email_verified_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Ошибка создания пользователя: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ошибка регистрации. Попробуйте снова.']);
        }

        // Очищаем сессию
        session()->forget(['verification_email', 'verification_name', 'verification_password']);

        // Логиним пользователя
        auth()->login($user);

        Log::info('Успешная регистрация', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('home')->with('success', 'Добро пожаловать в TSCHOOL OF DEATH!');
    }
}
