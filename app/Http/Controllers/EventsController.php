<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventsController extends Controller
{
    public function index()
    {
        return view("events.index");
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $eventData=request()->except(['_token', '_method']);
        Event::create($eventData);
    }

    public function show($id)
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        Event::destroy($id);
        return response()->json($id);
    }
}
