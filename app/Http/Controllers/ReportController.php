<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        if (auth()->user()->canDo('reports.view_all')) {
            $reports = Report::orderBy('id', 'desc')->paginate(10);
        } else {
            $reports = Report::where('user_id', auth()->id())->orderBy('id', 'desc')->paginate(10);
        }
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole('user')) {
            abort(403, 'Только обычные пользователи могут создавать репорты');
        }
        return view('reports.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('user')) {
            abort(403, 'Только обычные пользователи могут создавать репорты');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'player_name' => 'required|string|max:255',
            'player_steam_id' => 'nullable|string',
            'server_name' => 'nullable|string',
            'evidence_link' => 'nullable|url',
            'priority' => 'required|in:low,medium,high',
        ]);

        Report::create([
            'title' => $request->title,
            'description' => $request->description,
            'player_name' => $request->player_name,
            'player_steam_id' => $request->player_steam_id,
            'server_name' => $request->server_name,
            'evidence_link' => $request->evidence_link,
            'priority' => $request->priority,
            'status' => 'open',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reports.index')->with('success', 'Репорт отправлен администратору');
    }

    public function show(Report $report)
    {
        if ($report->user_id !== auth()->id() && !auth()->user()->canDo('reports.view_all')) {
            abort(403);
        }
        return view('reports.show', compact('report'));
    }

    public function destroy(Report $report)
    {
        if ($report->user_id !== auth()->id() && !auth()->user()->canDo('reports.delete')) {
            abort(403);
        }
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Репорт удалён');
    }

    public function addComment(Request $request, Report $report)
    {
        // Владелец всегда может комментировать свой репорт
        if ($report->user_id !== auth()->id() && !auth()->user()->canDo('reports.comment_all')) {
            abort(403);
        }

        $request->validate([
            'comment' => 'required|string|max:1000',
            'type' => 'in:public,private'
        ]);

        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'type' => $request->type ?? 'public'
        ]);

        return redirect()->back()->with('success', 'Комментарий добавлен');
    }

    public function closeWithResolution(Request $request, Report $report)
    {
        if (!auth()->user()->canDo('reports.change_status')) {
            abort(403, 'Нет права менять статус репорта');
        }

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

        return redirect()->route('reports.index')->with('success', 'Репорт закрыт с решением');
    }
}
