<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,closed',
            'deadline' => 'nullable|date',
        ]);

        Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status,
            'deadline' => $request->deadline,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('tickets.index')->with('success', 'Тикет создан');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,closed',
            'deadline' => 'nullable|date',
        ]);

        $ticket->update($request->all());

        return redirect()->route('tickets.index')->with('success', 'Тикет обновлён');
    }

    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Тикет удалён');
    }
}
