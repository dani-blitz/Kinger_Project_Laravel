<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('start_time', 'asc')->paginate(10);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
        ], [
            'start_time.after' => 'Дата начала должна быть позже текущего момента!',
            'end_time.after' => 'Дата окончания должна быть позже даты начала!',
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => Carbon::parse($request->end_time),
            'location' => $request->location,
        ]);

        return redirect()->route('events.index')->with('success', 'Событие создано');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'nullable|date|after:now',
            'end_time' => 'nullable|date|after:start_time',
            'location' => 'nullable|string|max:255',
        ], [
            'start_time.after' => 'Дата начала должна быть позже текущего момента!',
            'end_time.after' => 'Дата окончания должна быть позже даты начала!',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
        ];

        if ($request->start_time) {
            $data['start_time'] = Carbon::parse($request->start_time);
        }
        if ($request->end_time) {
            $data['end_time'] = Carbon::parse($request->end_time);
        }

        $event->update($data);
        return redirect()->route('events.index')->with('success', 'Событие обновлено');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Событие удалено');
    }
}
