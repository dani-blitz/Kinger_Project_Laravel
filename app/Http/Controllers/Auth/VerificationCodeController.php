<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
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

        $code = sprintf("%06d", mt_rand(1, 999999));

        EmailVerificationCode::where('email', $email)->delete();
        EmailVerificationCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'is_verified' => false,
        ]);

        // Отправляем через RabbitMQ
        SendEmailJob::dispatch($email, $code);

        session([
            'verification_email' => $email,
            'verification_name' => $name,
            'verification_password' => $password,
        ]);

        return redirect()->route('verification.form')->with('success', '🔮 КОД ОТПРАВЛЕН НА ПОЧТУ ЧЕРЕЗ RABBITMQ!');
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

        $verification->update(['is_verified' => true]);

        $user = User::create([
            'name' => session('verification_name'),
            'email' => $email,
            'password' => bcrypt(session('verification_password')),
            'email_verified_at' => now(),
        ]);

        session()->forget(['verification_email', 'verification_name', 'verification_password']);

        auth()->login($user);

        return redirect()->route('home')->with('success', '🔪 ДУША ПРОДАНА! ДОБРО ПОЖАЛОВАТЬ В АД! 🔪');
    }
}
