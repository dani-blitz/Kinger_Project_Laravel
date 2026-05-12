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
        $totalUsers = User::count();
        $totalEvents = Event::count();
        $totalTickets = Ticket::count();
        $failedLogs = FailedEmailLog::count();

        $usersToday = User::whereDate('created_at', today())->count();
        $usersWeek = User::whereBetween('created_at', [now()->subWeek(), now()])->count();
        $usersMonth = User::whereBetween('created_at', [now()->subMonth(), now()])->count();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalEvents', 'totalTickets', 'failedLogs',
            'usersToday', 'usersWeek', 'usersMonth'
        ));
    }
}
