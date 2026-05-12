<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\FailedEmailLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== ОСНОВНАЯ СТАТИСТИКА ==========
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalTickets = Ticket::count();
        $totalFailedLogs = FailedEmailLog::count();

        // ========== ВСЕГО ЗАПРОСОВ ==========
        // Всего попыток регистрации (успешные + ошибки)
        $totalRegistrationAttempts = $totalUsers + $totalFailedLogs;

        // Всего запросов на отправку кода
        $totalCodeRequests = $totalFailedLogs; // каждая ошибка = запрос кода

        // Успешных отправок кода (без ошибок)
        $successfulCodeSends = $totalUsers - FailedEmailLog::where('email', '!=', '')->distinct('email')->count();

        // ========== СТАТИСТИКА РЕГИСТРАЦИЙ ==========
        $usersToday = User::whereDate('created_at', today())->count();
        $usersWeek = User::whereBetween('created_at', [now()->subWeek(), now()])->count();
        $usersMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();

        $successfulRegistrations = User::whereNotNull('email_verified_at')->count();
        $successPercent = $totalUsers > 0 ? round(($successfulRegistrations / $totalUsers) * 100, 1) : 0;
        $errorRate = $totalRegistrationAttempts > 0 ? round(($totalFailedLogs / $totalRegistrationAttempts) * 100, 1) : 0;

        // ========== СТАТИСТИКА ОШИБОК ПОЧТЫ ==========
        $allLogs = FailedEmailLog::all();

        $smtpErrors = 0;
        $connectionErrors = 0;
        $authErrors = 0;
        $timeoutErrors = 0;
        $invalidEmailErrors = 0;

        foreach ($allLogs as $log) {
            $msg = $log->error_message;

            if (stripos($msg, 'authentication') !== false || stripos($msg, 'Authent') !== false) {
                $authErrors++;
            } elseif (stripos($msg, 'timeout') !== false) {
                $timeoutErrors++;
            } elseif (stripos($msg, 'invalid') !== false && stripos($msg, 'email') !== false) {
                $invalidEmailErrors++;
            } elseif (stripos($msg, 'Connection') !== false || stripos($msg, 'connect') !== false) {
                $connectionErrors++;
            } elseif (stripos($msg, 'SMTP') !== false) {
                $smtpErrors++;
            }
        }

        $totalCategorized = $smtpErrors + $connectionErrors + $authErrors + $timeoutErrors + $invalidEmailErrors;
        $otherErrors = $totalFailedLogs - $totalCategorized;

        // Проценты от общего числа ошибок
        $smtpPercent = $totalFailedLogs > 0 ? round(($smtpErrors / $totalFailedLogs) * 100, 1) : 0;
        $connectionPercent = $totalFailedLogs > 0 ? round(($connectionErrors / $totalFailedLogs) * 100, 1) : 0;
        $authPercent = $totalFailedLogs > 0 ? round(($authErrors / $totalFailedLogs) * 100, 1) : 0;
        $timeoutPercent = $totalFailedLogs > 0 ? round(($timeoutErrors / $totalFailedLogs) * 100, 1) : 0;
        $invalidEmailPercent = $totalFailedLogs > 0 ? round(($invalidEmailErrors / $totalFailedLogs) * 100, 1) : 0;
        $otherPercent = $totalFailedLogs > 0 ? round(($otherErrors / $totalFailedLogs) * 100, 1) : 0;

        // ========== СТАТИСТИКА ОШИБОК КОДА ==========
        $invalidCodeErrors = 0;
        $expiredCodeErrors = 0;
        $wrongEmailErrors = 0;

        foreach ($allLogs as $log) {
            $msg = $log->error_message;

            if (stripos($msg, 'Неверный код') !== false || stripos($msg, 'цифр') !== false) {
                $invalidCodeErrors++;
            } elseif (stripos($msg, 'просрочен') !== false || stripos($msg, 'истек') !== false) {
                $expiredCodeErrors++;
            } elseif (stripos($msg, 'email') !== false && (stripos($msg, 'невер') !== false || stripos($msg, 'Invalid') !== false)) {
                $wrongEmailErrors++;
            }
        }

        $totalCodeErrors = $invalidCodeErrors + $expiredCodeErrors + $wrongEmailErrors;

        // Проценты от общего числа ошибок
        $codeErrorsPercent = $totalFailedLogs > 0 ? round(($totalCodeErrors / $totalFailedLogs) * 100, 1) : 0;
        $invalidCodePercent = $totalFailedLogs > 0 ? round(($invalidCodeErrors / $totalFailedLogs) * 100, 1) : 0;
        $expiredCodePercent = $totalFailedLogs > 0 ? round(($expiredCodeErrors / $totalFailedLogs) * 100, 1) : 0;
        $wrongEmailPercent = $totalFailedLogs > 0 ? round(($wrongEmailErrors / $totalFailedLogs) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'totalTickets', 'totalFailedLogs',
            'totalRegistrationAttempts', 'totalCodeRequests', 'successfulCodeSends',
            'usersToday', 'usersWeek', 'usersMonth',
            'successfulRegistrations', 'successPercent', 'errorRate',
            'smtpErrors', 'connectionErrors', 'authErrors', 'timeoutErrors',
            'invalidEmailErrors', 'otherErrors',
            'smtpPercent', 'connectionPercent', 'authPercent', 'timeoutPercent',
            'invalidEmailPercent', 'otherPercent',
            'invalidCodeErrors', 'expiredCodeErrors', 'wrongEmailErrors',
            'totalCodeErrors', 'codeErrorsPercent',
            'invalidCodePercent', 'expiredCodePercent', 'wrongEmailPercent'
        ));
    }
}
