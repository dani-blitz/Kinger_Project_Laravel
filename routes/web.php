<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Стандартная аутентификация (без регистрации)
Auth::routes(['register' => false]);

// Кастомная регистрация с верификацией
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Выход
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Маршруты для верификации по коду
Route::post('/verification/send', [VerificationCodeController::class, 'sendCode'])->name('verification.send');
Route::get('/verification/form', [VerificationCodeController::class, 'showForm'])->name('verification.form');
Route::post('/verification/verify', [VerificationCodeController::class, 'verifyCode'])->name('verification.verify');

// Защищённые маршруты
Route::middleware(['auth'])->group(function () {
    Route::resource('events', EventController::class);
    Route::get('/categories', [EventController::class, 'index'])->name('categories.index');

    Route::resource('tickets', TicketController::class);
    Route::get('/comments', [TicketController::class, 'index'])->name('comments.index');
});

// Переключение тем
Route::get('/theme/{theme}', [ThemeController::class, 'switch'])->name('theme.switch');
