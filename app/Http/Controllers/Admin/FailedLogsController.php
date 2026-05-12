<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedEmailLog;

class FailedLogsController extends Controller
{
    public function index()
    {
        $logs = FailedEmailLog::orderBy('id', 'desc')->paginate(20);
        return view('admin.failed-logs', compact('logs'));
    }
}
