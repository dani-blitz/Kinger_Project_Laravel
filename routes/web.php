<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\FailedLogsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReportController;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerController;

Route::get('/', function () {
    return view('welcome');
});

// ========== АУТЕНТИФИКАЦИЯ ==========

// Страница логина
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Кастомная регистрация с верификацией
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Выход
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ========== ВЕРИФИКАЦИЯ КОДА ==========
Route::post('/verification/send', [VerificationCodeController::class, 'sendCode'])->name('verification.send');
Route::get('/verification/form', [VerificationCodeController::class, 'showForm'])->name('verification.form');
Route::post('/verification/verify', [VerificationCodeController::class, 'verifyCode'])->name('verification.verify');

// ========== HOME ==========
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ========== ЗАЩИЩЁННЫЕ МАРШРУТЫ ==========
Route::middleware(['auth'])->group(function () {
    Route::resource('news', NewsController::class);
    Route::resource('reports', ReportController::class);

    // Комментарии к репортам
    Route::post('/reports/{report}/comment', [ReportController::class, 'addComment'])->name('reports.comment');

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
    Route::post('/reports/{report}/comment', [ReportController::class, 'addComment'])->name('reports.comment');
});

// ========== АДМИН-ПАНЕЛЬ (только для админов) ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/users/{id}/make-admin', [UsersController::class, 'makeAdmin'])->name('users.make-admin');
    Route::get('/failed-logs', [FailedLogsController::class, 'index'])->name('failed-logs');
    Route::get('/pending-news', [NewsController::class, 'pending'])->name('pending-news');
    Route::post('/news/{id}/approve', [NewsController::class, 'approve'])->name('news.approve');
    Route::post('/news/{id}/reject', [NewsController::class, 'reject'])->name('news.reject');
    Route::get('/servers', [App\Http\Controllers\Admin\ServerController::class, 'index'])->name('servers');
});

// ========== ПЕРЕКЛЮЧЕНИЕ ТЕМ ==========
Route::get('/theme/{theme}', [ThemeController::class, 'switch'])->name('theme.switch');

// ========== ТЕСТОВЫЕ МАРШРУТЫ ==========
Route::get('/test', function () {
    return '✅ Сервер работает!';
});

Route::get('/test-queue', function () {
    try {
        SendEmailJob::dispatch('test@example.com', '123456');
        Log::info('✅ Задача отправлена в RabbitMQ');
        return '✅ Задача отправлена в очередь RabbitMQ!';
    } catch (\Exception $e) {
        Log::error('❌ Ошибка RabbitMQ: ' . $e->getMessage());
        return '❌ Ошибка: ' . $e->getMessage();
    }
});

Route::patch('/reports/{id}/update', [ReportsController::class, 'update'])->name('reports.update');
Route::delete('/reports/{id}', [ReportsController::class, 'destroy'])->name('reports.destroy');

Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');
