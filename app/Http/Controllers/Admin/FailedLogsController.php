<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FailedEmailError;
use App\Models\FailedCodeError;

class FailedLogsController extends Controller
{
    public function index()
    {
        $emailLogs = FailedEmailError::orderBy('id', 'desc')->paginate(20);
        $codeLogs = FailedCodeError::orderBy('id', 'desc')->paginate(20);

        return view('admin.failed-logs', compact('emailLogs', 'codeLogs'));
    }
}
