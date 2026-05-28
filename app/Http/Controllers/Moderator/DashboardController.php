<?php

namespace App\Http\Controllers\Moderator;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
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
            $count = Report::whereDate('created_at', $date)->count();
            $reportsByDay30[$date] = $count;
        }

        $reports = Report::orderBy('id', 'desc')->paginate(20);

        return view('moderator.dashboard', compact(
            'totalReports', 'openReports', 'inProgressReports', 'closedReports',
            'topOffenders', 'topServers', 'reportsByDay30', 'reports'
        ));
    }
}
