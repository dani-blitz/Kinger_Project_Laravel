<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\BannedWord;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function reportsStats()
    {
        $totalReports = Report::count();
        $openReports = Report::where('status', 'open')->count();
        $inProgressReports = Report::where('status', 'in_progress')->count();
        $closedReports = Report::where('status', 'closed')->count();

        $topOffenders = Report::select('player_name', DB::raw('count(*) as total'))
            ->whereNotNull('player_name')
            ->groupBy('player_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $topServers = Report::select('server_name', DB::raw('count(*) as total'))
            ->whereNotNull('server_name')
            ->groupBy('server_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $reportsByDay30 = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $reportsByDay30[$date] = Report::whereDate('created_at', $date)->count();
        }

        $statusHistory = [
            'open' => $openReports,
            'in_progress' => $inProgressReports,
            'closed' => $closedReports,
        ];

        $avgCloseTime = Report::whereNotNull('updated_at')
            ->where('status', 'closed')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
            ->value('avg_hours') ?? 0;

        // ========== ПОПУЛЯРНЫЕ СЛОВА (динамический бан-лист) ==========
        $allReports = Report::all();
        $wordCount = [];

        $stopWords = BannedWord::getStopWordsArray();
        if (empty($stopWords)) {
            $stopWords = ['и', 'в', 'на', 'с', 'по', 'к', 'у', 'о', 'за', 'из', 'от', 'до', 'а', 'но', 'или', 'the', 'and', 'of', 'to', 'in', 'for', 'on', 'with', 'by', 'at'];
        }

        foreach ($allReports as $report) {
            $text = mb_strtolower($report->title . ' ' . $report->description);
            $text = preg_replace('/[^\p{L}\p{M}\s]/u', ' ', $text);
            $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($words as $word) {
                if (mb_strlen($word) < 3) continue;
                if (in_array($word, $stopWords)) continue;
                $wordCount[$word] = ($wordCount[$word] ?? 0) + 1;
            }
        }

        arsort($wordCount);
        $topWords = array_slice($wordCount, 0, 15, true);

        return view('admin.stats-reports', compact(
            'totalReports', 'openReports', 'inProgressReports', 'closedReports',
            'topOffenders', 'topServers', 'reportsByDay30', 'statusHistory', 'avgCloseTime', 'topWords'
        ));
    }

    // Статистика ошибок (оставьте ваш существующий код)
    public function errorsStats()
    {
        $totalEmailErrors = \App\Models\FailedEmailError::count();
        $totalCodeErrors = \App\Models\FailedCodeError::count();
        $totalFailedLogs = $totalEmailErrors + $totalCodeErrors;

        $totalUsers = \App\Models\User::count();
        $totalAttempts = $totalUsers + $totalFailedLogs;
        $errorRate = $totalAttempts > 0 ? round(($totalFailedLogs / $totalAttempts) * 100, 1) : 0;

        // Ошибки почты
        $smtpErrors = \App\Models\FailedEmailError::where('error_type', 'smtp')->count();
        $connectionErrors = \App\Models\FailedEmailError::where('error_type', 'connection')->count();
        $authErrors = \App\Models\FailedEmailError::where('error_type', 'auth')->count();
        $timeoutErrors = \App\Models\FailedEmailError::where('error_type', 'timeout')->count();
        $otherEmailErrors = \App\Models\FailedEmailError::where('error_type', 'unknown')->count();

        $totalEmailErrorsCount = $totalEmailErrors;
        $smtpPercent = $totalEmailErrorsCount > 0 ? round(($smtpErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $connectionPercent = $totalEmailErrorsCount > 0 ? round(($connectionErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $authPercent = $totalEmailErrorsCount > 0 ? round(($authErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $timeoutPercent = $totalEmailErrorsCount > 0 ? round(($timeoutErrors / $totalEmailErrorsCount) * 100, 1) : 0;
        $otherEmailPercent = $totalEmailErrorsCount > 0 ? round(($otherEmailErrors / $totalEmailErrorsCount) * 100, 1) : 0;

        // Ошибки кода
        $invalidCodeErrors = \App\Models\FailedCodeError::where('error_type', 'invalid')->count();
        $expiredCodeErrors = \App\Models\FailedCodeError::where('error_type', 'expired')->count();
        $wrongEmailErrors = \App\Models\FailedCodeError::where('error_type', 'wrong_email')->count();
        $formatCodeErrors = \App\Models\FailedCodeError::where('error_type', 'format')->count();

        $totalCodeErrorsCount = $totalCodeErrors;
        $invalidCodePercent = $totalCodeErrorsCount > 0 ? round(($invalidCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $expiredCodePercent = $totalCodeErrorsCount > 0 ? round(($expiredCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $wrongEmailPercent = $totalCodeErrorsCount > 0 ? round(($wrongEmailErrors / $totalCodeErrorsCount) * 100, 1) : 0;
        $formatCodePercent = $totalCodeErrorsCount > 0 ? round(($formatCodeErrors / $totalCodeErrorsCount) * 100, 1) : 0;

        return view('admin.stats-errors', compact(
            'totalFailedLogs', 'totalEmailErrorsCount', 'totalCodeErrorsCount', 'errorRate',
            'smtpErrors', 'connectionErrors', 'authErrors', 'timeoutErrors', 'otherEmailErrors',
            'smtpPercent', 'connectionPercent', 'authPercent', 'timeoutPercent', 'otherEmailPercent',
            'invalidCodeErrors', 'expiredCodeErrors', 'wrongEmailErrors', 'formatCodeErrors',
            'invalidCodePercent', 'expiredCodePercent', 'wrongEmailPercent', 'formatCodePercent'
        ));
    }
}
