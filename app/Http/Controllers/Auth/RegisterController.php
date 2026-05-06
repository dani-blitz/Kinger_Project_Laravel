<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationCode;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/verification/form';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $email = $request->email;
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Сохраняем код
        EmailVerificationCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'is_verified' => false,
            ]
        );

        // Сохраняем данные в сессию
        session([
            'verification_email' => $email,
            'verification_name' => $request->name,
            'verification_password' => $request->password,
        ]);

        // Отправляем письмо
        Mail::raw("🔮 ТВОЙ КОД: $code 🔮\n\nАд ждёт тебя!", function ($message) use ($email) {
            $message->to($email)
                ->subject('🔪 Код подтверждения - TSCHOOL OF DEATH 🔪');
        });

        return redirect()->route('verification.form');
    }
}
