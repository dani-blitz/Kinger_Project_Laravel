<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\News;
use App\Models\Report;
use App\Models\FailedEmailError;
use App\Models\FailedCodeError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== ОСНОВНАЯ СТАТИСТИКА ==========
        $totalUsers = User::count();
        $totalNews = News::count();
        $totalReports = Report::count();
        $totalEmailErrors = FailedEmailError::count();
        $totalCodeErrors = FailedCodeError::count();
        $totalFailedLogs = $totalEmailErrors + $totalCodeErrors;

        $openReports = Report::where('status', 'open')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $closedReports = Report::where('status', 'closed')->count();

        // ========== ТОЛЬКО ПОДТВЕРЖДЁННЫЕ ПОЛЬЗОВАТЕЛИ ==========
        $confirmedUsers = User::whereNotNull('email_verified_at')->count();

        // ========== СТАТИСТИКА РЕГИСТРАЦИЙ ==========
        $usersToday = User::whereNotNull('email_verified_at')->whereDate('email_verified_at', today())->count();
        $usersWeek = User::whereNotNull('email_verified_at')->whereBetween('email_verified_at', [now()->subWeek(), now()])->count();
        $usersMonth = User::whereNotNull('email_verified_at')->whereBetween('email_verified_at', [now()->subMonth(), now()])->count();

        // ========== СТАТИСТИКА ОШИБОК ==========
        $totalAttempts = $totalUsers + $totalFailedLogs;
        $errorRate = $totalAttempts > 0 ? round(($totalFailedLogs / $totalAttempts) * 100, 1) : 0;

        // ========== ПОПУЛЯРНЫЕ СЛОВА В РЕПОРТАХ ==========
        $allReports = Report::all();
        $wordCount = [];

        $stopWords = [
            'и', 'в', 'на', 'с', 'по', 'к', 'у', 'о', 'за', 'из', 'от', 'до',
            'а', 'но', 'или', 'так', 'же', 'бы', 'это', 'что', 'как', 'для',
            'the', 'and', 'of', 'to', 'in', 'for', 'on', 'with', 'by', 'at',
            'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has',
            'had', 'having', 'do', 'does', 'did', 'doing', 'but', 'or', 'so',
            'if', 'then', 'else', 'when', 'where', 'which', 'while', 'who',
            'whom', 'this', 'that', 'these', 'those', 'some', 'any', 'no',
            'very', 'just', 'not', 'only', 'really', 'player', 'игрок'
        ];

        foreach ($allReports as $report) {
            $text = strtolower($report->title . ' ' . $report->description);
            $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
            $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($words as $word) {
                if (mb_strlen($word) < 3) continue;
                if (in_array($word, $stopWords)) continue;

                if (!isset($wordCount[$word])) $wordCount[$word] = 0;
                $wordCount[$word]++;
            }
        }

        arsort($wordCount);
        $topWords = array_slice($wordCount, 0, 15, true);

        // ========== РАСШИРЕННАЯ СТАТИСТИКА ==========

        // Топ нарушителей (кто чаще всего попадает в репорты)
        $topOffenders = Report::select('player_name', DB::raw('count(*) as total'))
            ->whereNotNull('player_name')
            ->groupBy('player_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Топ серверов по количеству репортов
        $topServers = Report::select('server_name', DB::raw('count(*) as total'))
            ->whereNotNull('server_name')
            ->groupBy('server_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Репорты по дням (улучшенный график с 30 днями)
        $reportsByDay30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Report::whereDate('created_at', $date)->count();
            $reportsByDay30[$date] = $count;
        }

        // Динамика изменения статусов
        $statusHistory = [
            'open' => Report::where('status', 'open')->count(),
            'in_progress' => Report::where('status', 'in_progress')->count(),
            'closed' => Report::where('status', 'closed')->count(),
        ];

        // Среднее время закрытия репорта (в часах)
        $avgCloseTime = Report::whereNotNull('updated_at')
            ->where('status', 'closed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // ========== СТАТИСТИКА ОШИБОК ПОЧТЫ ==========
        $smtpErrors = FailedEmailError::where('error_type', 'smtp')->count();
        $connectionErrors = FailedEmailError::where('error_type', 'connection')->count();
        $authErrors = FailedEmailError::where('error_type', 'auth')->count();
        $timeoutErrors = FailedEmailError::where('error_type', 'timeout')->count();
        $otherEmailErrors = FailedEmailError::where('error_type', 'unknown')->count();

        $totalEmailErrorsCount = $totalEmailErrors;

        $smtpPercent = $totalEmailErrorsCount > 0 ? round(($smtpErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $connectionPercent = $totalEmailErrorsCount > 0 ? round(($connectionErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $authPercent = $totalEmailErrorsCount > 0 ? round(($authErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $timeoutPercent = $totalEmailErrorsCount > 0 ? round(($timeoutErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $otherEmailPercent = $totalEmailErrorsCount > 0 ? round(($otherEmailErrors / $totalEmailErrorsCount) * 100, 1) : 0;

        // ========== СТАТИСТИКА ОШИБОК КОДА ==========
        $invalidCodeErrors = FailedCodeError::where('error_type', 'invalid')->count();
        $expiredCodeErrors = FailedCodeError::where('error_type', 'expired')->count();
        $wrongEmailErrors = FailedCodeError::where('error_type', 'wrong_email')->count();
        $formatCodeErrors = FailedCodeError::where('error_type', 'format')->count();

        $totalCodeErrorsCount = $totalCodeErrors;

        $invalidCodePercent = $totalCodeErrorsCount > 0 ? round(($invalidCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $expiredCodePercent = $totalCodeErrorsCount > 0 ? round(($expiredCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $wrongEmailPercent = $totalCodeErrorsCount > 0 ? round(($wrongEmailErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $formatCodePercent = $totalCodeErrorsCount > 0 ? round(($formatCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;

        return view('admin.dashboard', compact(
            'totalUsers', 'totalNews', 'totalReports', 'totalFailedLogs',
            'openReports', 'inProgressReports', 'closedReports',
            'confirmedUsers', 'usersToday', 'usersWeek', 'usersMonth', 'errorRate',
            'topWords',
            'topOffenders', 'topServers', 'reportsByDay30', 'statusHistory', 'avgCloseTime',
            'smtpErrors', 'connectionErrors', 'authErrors', 'timeoutErrors', 'otherEmailErrors',
            'smtpPercent', 'connectionPercent', 'authPercent', 'timeoutPercent', 'otherEmailPercent',
            'totalEmailErrorsCount', 'totalCodeErrorsCount',
            'invalidCodeErrors', 'expiredCodeErrors', 'wrongEmailErrors', 'formatCodeErrors',
            'invalidCodePercent', 'expiredCodePercent', 'wrongEmailPercent', 'formatCodePercent'
        ));
    }
}
