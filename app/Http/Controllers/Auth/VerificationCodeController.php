<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VerificationCodeController extends Controller
{
    // Показать форму ввода кода
    public function showForm(Request $request)
    {
        $email = $request->session()->get('verification_email');
        if (!$email) {
            return redirect()->route('register');
        }

        return view('auth.verify-code', compact('email'));
    }

    // Отправить код на почту
    public function sendCode(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $email = $request->email;
        $name = $request->name;
        $password = $request->password;

        // Генерируем 6-значный код
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Сохраняем в БД
        EmailVerificationCode::where('email', $email)->delete();
        EmailVerificationCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'is_verified' => false,
        ]);

        // Пишем в лог
        Log::info("КОД ПОДТВЕРЖДЕНИЯ ДЛЯ {$email}: {$code}");

        // Сохраняем в сессию
        session([
            'verification_email' => $email,
            'verification_name' => $name,
            'verification_password' => $password,
        ]);

        return redirect()->route('verification.form')->with('success', '🔮 КОД ОТПРАВЛЕН! ПРОВЕРЬ ПОЧТУ!');
    }

    // Проверить код
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = session('verification_email');
        if (!$email) {
            return redirect()->route('register')->with('error', 'Сессия истекла. Попробуйте снова.');
        }

        $verification = EmailVerificationCode::where('email', $email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return back()->with('error', '💀 НЕВЕРНЫЙ ИЛИ ПРОСРОЧЕННЫЙ КОД! 💀');
        }

        // Помечаем как подтверждённый
        $verification->update(['is_verified' => true]);

        // Создаём пользователя
        $user = User::create([
            'name' => session('verification_name'),
            'email' => $email,
            'password' => bcrypt(session('verification_password')),
            'email_verified_at' => now(),
        ]);

        // Очищаем сессию
        session()->forget(['verification_email', 'verification_name', 'verification_password']);

        // Логиним пользователя
        auth()->login($user);

        return redirect()->route('home')->with('success', '🔪 ДУША ПРОДАНА! ДОБРО ПОЖАЛОВАТЬ В АД! 🔪');
    }
}
