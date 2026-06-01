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

        // ========== ПОПУЛЯРНЫЕ СЛОВА В РЕПОРТАХ (расширенная фильтрация) ==========
        $allReports = Report::all();
        $wordCount = [];

// Расширенный стоп-лист (русские и английские союзы, предлоги, частицы, местоимения)
        $stopWords = [
            // Русские
            'и', 'в', 'во', 'на', 'с', 'со', 'к', 'ко', 'у', 'о', 'об', 'за', 'из', 'из-за', 'под', 'над', 'без', 'до', 'по', 'от', 'перед', 'через', 'для', 'при', 'между', 'сквозь', 'про', 'возле', 'около', 'мимо', 'вокруг', 'благодаря', 'согласно', 'вопреки', 'напротив', 'а', 'но', 'да', 'и', 'или', 'либо', 'то', 'так', 'же', 'ведь', 'вон', 'вот', 'ещё', 'лишь', 'почти', 'только', 'хоть', 'хотя', 'чтобы', 'что', 'чтобы', 'будто', 'как', 'словно', 'точно', 'пока', 'когда', 'если', 'раз', 'хотя', 'пусть', 'пускай', 'даже', 'неужели', 'разве', 'вдруг', 'едва', 'ли', 'уж', 'уже', 'все', 'всё', 'весь', 'вся', 'всех', 'всем', 'всеми', 'всего', 'всякого', 'всякому', 'всяческие', 'всячески', 'сам', 'сама', 'само', 'сами', 'самого', 'самой', 'самому', 'самим', 'самом', 'саму', 'самих', 'самими', 'свой', 'своя', 'своё', 'свои', 'своего', 'своей', 'своему', 'своим', 'своём', 'свою', 'своих', 'своими', 'мой', 'моя', 'моё', 'мои', 'моего', 'моей', 'моему', 'моим', 'моём', 'мою', 'моих', 'моими', 'твой', 'твоя', 'твоё', 'твои', 'твоего', 'твоей', 'твоему', 'твоим', 'твоём', 'твою', 'твоих', 'твоими', 'наш', 'наша', 'наше', 'наши', 'нашего', 'нашей', 'нашему', 'нашим', 'нашем', 'нашу', 'наших', 'нашими', 'ваш', 'ваша', 'ваше', 'ваши', 'вашего', 'вашей', 'вашему', 'вашим', 'вашем', 'вашу', 'ваших', 'вашими', 'он', 'она', 'оно', 'они', 'его', 'её', 'их', 'ему', 'ей', 'им', 'нём', 'ней', 'них', 'ним', 'неё', 'неё', 'него', 'неё', 'нею', 'ими', 'оно', 'этот', 'эта', 'это', 'эти', 'этого', 'этой', 'этому', 'этим', 'этом', 'эту', 'этих', 'этими', 'тот', 'та', 'то', 'те', 'того', 'той', 'тому', 'тем', 'том', 'ту', 'тех', 'теми', 'такой', 'такая', 'такое', 'такие', 'такого', 'такой', 'такому', 'таким', 'таком', 'такую', 'таких', 'такими', 'какой', 'какая', 'какое', 'какие', 'какого', 'какой', 'какому', 'каким', 'каком', 'какую', 'каких', 'какими', 'который', 'которая', 'которое', 'которые', 'которого', 'которой', 'которому', 'которым', 'котором', 'которую', 'которых', 'которыми', 'бы', 'было', 'была', 'были', 'был', 'будет', 'будут', 'будь', 'буду', 'будешь', 'будем', 'будете', 'быть', 'является', 'являются', 'являлся', 'являлась', 'являлось', 'являлись', 'есть', 'суть', 'стал', 'стала', 'стало', 'стали', 'становиться', 'становится', 'стать', 'становился', 'становилась', 'становилось', 'становились', 'это', 'этот', 'эта', 'эти', 'этот', 'этих', 'этим', 'эту', 'те', 'тем', 'ту', 'то',

            // Английские
            'the', 'and', 'of', 'to', 'in', 'for', 'on', 'with', 'by', 'at', 'from', 'as', 'is', 'was', 'are', 'were', 'been', 'be', 'have', 'has', 'had', 'having', 'do', 'does', 'did', 'doing', 'but', 'or', 'so', 'for', 'nor', 'yet', 'so', 'such', 'both', 'either', 'neither', 'not', 'only', 'just', 'very', 'really', 'actually', 'basically', 'practically', 'virtually', 'that', 'this', 'these', 'those', 'there', 'their', 'they', 'them', 'we', 'our', 'us', 'you', 'your', 'he', 'she', 'it', 'its', 'him', 'her', 'his', 'hers', 'theirs', 'ours', 'yours', 'myself', 'yourself', 'himself', 'herself', 'itself', 'ourselves', 'yourselves', 'themselves', 'all', 'each', 'every', 'some', 'any', 'no', 'none', 'many', 'much', 'more', 'most', 'few', 'little', 'less', 'least', 'enough', 'several', 'both', 'neither', 'either', 'everyone', 'everybody', 'everything', 'someone', 'somebody', 'something', 'anyone', 'anybody', 'anything', 'no one', 'nobody', 'nothing', 'whom', 'whose', 'which', 'what', 'when', 'where', 'why', 'how', 'then', 'than', 'so', 'such', 'both', 'each', 'every', 'after', 'before', 'above', 'below', 'between', 'among', 'through', 'during', 'without', 'within', 'along', 'across', 'against', 'behind', 'beneath', 'beside', 'beyond', 'circa', 'except', 'excluding', 'including', 'like', 'near', 'off', 'onto', 'opposite', 'outside', 'over', 'past', 'plus', 'since', 'throughout', 'toward', 'under', 'underneath', 'unlike', 'until', 'up', 'upon', 'via', 'with', 'within', 'without', 'worth', 'per', 'a', 'an'
        ];

        foreach ($allReports as $report) {
            // Берём заголовок + описание, приводим к нижнему регистру
            $text = mb_strtolower($report->title . ' ' . $report->description);

            // Удаляем всё, кроме букв и пробелов (убираем цифры, знаки препинания)
            $text = preg_replace('/[^\p{L}\p{M}\s]/u', ' ', $text);

            // Разбиваем на слова
            $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($words as $word) {
                // Игнорируем слова короче 3 букв
                if (mb_strlen($word) < 3) {
                    continue;
                }
                // Игнорируем стоп-слова
                if (in_array($word, $stopWords)) {
                    continue;
                }
                // Дополнительно: можно игнорировать слова, состоящие только из одинаковых букв (aaa, bbb)
                if (preg_match('/^(.)\1{2,}$/u', $word)) {
                    continue;
                }

                $wordCount[$word] = ($wordCount[$word] ?? 0) + 1;
            }
        }

// Сортируем по убыванию, берём топ-15
        arsort($wordCount);
        $topWords = array_slice($wordCount, 0, 15, true);

        // ========== РАСШИРЕННАЯ СТАТИСТИКА ==========

        // Топ нарушителей
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

        // Репорты по дням (30 дней)
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

        // ========== СПИСОК РЕПОРТОВ ДЛЯ ТАБЛИЦЫ ==========
        $reports = Report::orderBy('id', 'desc')->paginate(20);

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

        // ========== ПРАВА ДОСТУПА ==========
        $canViewReports = auth()->user()->canDo('reports.view_all');
        $canViewUsers = auth()->user()->canDo('users.view');
        $canViewNews = auth()->user()->canDo('news.view');

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
            'invalidCodePercent', 'expiredCodePercent', 'wrongEmailPercent', 'formatCodePercent',
            'canViewReports', 'canViewUsers', 'canViewNews',
            'reports'
        ));
    }
}
