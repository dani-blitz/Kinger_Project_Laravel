<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('id', 'desc')->paginate(20);
        return view('admin.dashboard', compact('reports'));
    }

    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();
        return redirect()->back()->with('success', 'Статус обновлён');
    }

    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->back()->with('success', 'Репорт удалён');
    }
}
