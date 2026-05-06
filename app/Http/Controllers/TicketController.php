<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        Ticket::create([
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority,
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Тикет создан');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $ticket->update($request->all());
        return redirect()->route('tickets.index')->with('success', 'Тикет обновлён');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Тикет удалён');
    }
}
