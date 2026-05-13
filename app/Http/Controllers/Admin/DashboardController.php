<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\FailedEmailError;
use App\Models\FailedCodeError;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== ОСНОВНАЯ СТАТИСТИКА ==========
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalTickets = Ticket::count();
        $totalEmailErrors = FailedEmailError::count();
        $totalCodeErrors = FailedCodeError::count();
        $totalFailedLogs = $totalEmailErrors + $totalCodeErrors;

        // ========== ВСЕГО ЗАПРОСОВ ==========
        $successfulRegistrations = User::whereNotNull('email_verified_at')->count();

        // ВСЕГО ПОПЫТОК = успешные + ошибки кода + ошибки почты
        $totalRegistrationAttempts = $successfulRegistrations + $totalCodeErrors + $totalEmailErrors;
        $totalCodeRequests = $totalCodeErrors;
        $successfulCodeSends = $successfulRegistrations;

        // ========== СТАТИСТИКА РЕГИСТРАЦИЙ ==========
        $usersToday = User::whereDate('created_at', today())->count();
        $usersWeek = User::whereBetween('created_at', [now()->subWeek(), now()])->count();
        $usersMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();

        $successPercent = $totalRegistrationAttempts > 0 ? round(($successfulRegistrations / $totalRegistrationAttempts) * 100, 1) : 0;
        $errorRate = $totalRegistrationAttempts > 0 ? round(($totalFailedLogs / $totalRegistrationAttempts) * 100, 1) : 0;

        // ========== СТАТИСТИКА ОШИБОК ПОЧТЫ ==========
        $smtpErrors = FailedEmailError::where('error_type', 'smtp')->count();
        $connectionErrors = FailedEmailError::where('error_type', 'connection')->count();
        $authErrors = FailedEmailError::where('error_type', 'auth')->count();
        $timeoutErrors = FailedEmailError::where('error_type', 'timeout')->count();
        $otherEmailErrors = FailedEmailError::where('error_type', 'unknown')->count();

        $smtpPercent = $totalEmailErrors > 0 ? round(($smtpErrors / $totalEmailErrors) * 100, 1) : 0;
        $connectionPercent = $totalEmailErrors > 0 ? round(($connectionErrors / $totalEmailErrors) * 100, 1) : 0;
        $authPercent = $totalEmailErrors > 0 ? round(($authErrors / $totalEmailErrors) * 100, 1) : 0;
        $timeoutPercent = $totalEmailErrors > 0 ? round(($timeoutErrors / $totalEmailErrors) * 100, 1) : 0;
        $otherEmailPercent = $totalEmailErrors > 0 ? round(($otherEmailErrors / $totalEmailErrors) * 100, 1) : 0;

        // ========== СТАТИСТИКА ОШИБОК КОДА ==========
        $invalidCodeErrors = FailedCodeError::where('error_type', 'invalid')->count();
        $expiredCodeErrors = FailedCodeError::where('error_type', 'expired')->count();
        $wrongEmailErrors = FailedCodeError::where('error_type', 'wrong_email')->count();
        $formatCodeErrors = FailedCodeError::where('error_type', 'format')->count();

        $invalidCodePercent = $totalCodeErrors > 0 ? round(($invalidCodeErrors / $totalCodeErrors) * 100, 1) : 0;
        $expiredCodePercent = $totalCodeErrors > 0 ? round(($expiredCodeErrors / $totalCodeErrors) * 100, 1) : 0;
        $wrongEmailPercent = $totalCodeErrors > 0 ? round(($wrongEmailErrors / $totalCodeErrors) * 100, 1) : 0;
        $formatCodePercent = $totalCodeErrors > 0 ? round(($formatCodeErrors / $totalCodeErrors) * 100, 1) : 0;

        $codeErrorsPercent = $totalCodeRequests > 0 ? round(($totalCodeErrors / $totalCodeRequests) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'totalTickets', 'totalFailedLogs',
            'totalRegistrationAttempts', 'totalCodeRequests', 'successfulCodeSends',
            'usersToday', 'usersWeek', 'usersMonth',
            'successfulRegistrations', 'successPercent', 'errorRate',
            'smtpErrors', 'connectionErrors', 'authErrors', 'timeoutErrors', 'otherEmailErrors',
            'smtpPercent', 'connectionPercent', 'authPercent', 'timeoutPercent', 'otherEmailPercent',
            'totalEmailErrors', 'totalCodeErrors', 'codeErrorsPercent',
            'invalidCodeErrors', 'expiredCodeErrors', 'wrongEmailErrors', 'formatCodeErrors',
            'invalidCodePercent', 'expiredCodePercent', 'wrongEmailPercent', 'formatCodePercent'
        ));
    }
}
