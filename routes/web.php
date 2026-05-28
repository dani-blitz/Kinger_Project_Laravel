<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\VerificationCodeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\FailedLogsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServerController;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

// ========== ПУБЛИЧНЫЕ МАРШРУТЫ ==========
Route::get('/servers', [ServerController::class, 'index'])->name('servers.index');

// ========== ЗАЩИЩЁННЫЕ МАРШРУТЫ ==========
Route::middleware(['auth'])->group(function () {
    Route::resource('news', NewsController::class);
    Route::resource('reports', ReportController::class);

    Route::post('/reports/{report}/comment', [ReportController::class, 'addComment'])->name('reports.comment');
    Route::post('/reports/{report}/close', [ReportController::class, 'closeWithResolution'])->name('reports.close');

    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
});

// ========== АДМИН-ПАНЕЛЬ ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Главный дашборд (перенаправление на статистику репортов)
    Route::get('/dashboard', function () {
        return redirect()->route('admin.reports-stats');
    })->name('dashboard');

    // Статистика репортов
    Route::get('/reports-stats', [App\Http\Controllers\Admin\StatsController::class, 'reportsStats'])->name('reports-stats');

    // Статистика ошибок
    Route::get('/errors-stats', [App\Http\Controllers\Admin\StatsController::class, 'errorsStats'])->name('errors-stats');

    // Остальные админ-маршруты (пользователи, роли, логи ошибок, модерация новостей)
    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/failed-logs', [FailedLogsController::class, 'index'])->name('failed-logs');
    Route::get('/pending-news', [NewsController::class, 'pending'])->name('pending-news');
    Route::post('/news/{id}/approve', [NewsController::class, 'approve'])->name('news.approve');
    Route::post('/news/{id}/reject', [NewsController::class, 'reject'])->name('news.reject');

    // Управление ролями (только super_admin)
    Route::middleware(['check.permission:users.manage_roles'])->group(function () {
        Route::get('/users/manage-roles', [RoleController::class, 'index'])->name('users.manage-roles');
        Route::post('/users/{user}/update-role', [RoleController::class, 'updateRole'])->name('users.update-role');
        Route::post('/users/{user}/update-permissions', [RoleController::class, 'updatePermissions'])->name('users.update-permissions');
    });
});

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

// ========== ПАНЕЛЬ МОДЕРАТОРА ==========
Route::middleware(['auth', 'moderator'])->prefix('moderator')->name('moderator.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Moderator\DashboardController::class, 'index'])->name('dashboard');
});
