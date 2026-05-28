<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportComment;
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
        if (!auth()->user()->canDo('reports.change_status')) {
            abort(403);
        }
        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();
        return redirect()->back()->with('success', 'Статус обновлён');
    }

    public function destroy($id)
    {
        if (!auth()->user()->canDo('reports.delete')) {
            abort(403);
        }
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->back()->with('success', 'Репорт удалён');
    }

    public function closeWithResolution(Request $request, $id)
    {
        if (!auth()->user()->canDo('reports.change_status')) {
            abort(403);
        }

        $report = Report::findOrFail($id);
        $request->validate([
            'resolution' => 'required|string|min:5|max:1000',
        ]);

        $report->status = 'closed';
        $report->save();

        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => "✅ РЕШЕНИЕ: " . $request->resolution,
            'type' => 'public'
        ]);

        return redirect()->back()->with('success', 'Репорт закрыт с решением: ' . $request->resolution);
    }
}
