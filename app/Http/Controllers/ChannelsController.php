<?php

namespace App\Http\Controllers;

use App\Models\Channels;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $channels = Channels::all();

        return response()->json($channels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'clientsCount' => 'required',
        ]);

        if (Channels::where('name', $request->name)->exists()) {
            return response()->json(['error' => 'Channel with the same name already exists'], 400);
        }

        if ($request->clientsCount <= 0) {
            return response()->json(['error' => 'clientsCount must be greater than 0'], 400);
        }

        $newChannel = new Channels();
        $newChannel->name = $request->name;
        $newChannel->clientsCount = $request->clientsCount;
        $newChannel->save();
        
        return response()->json(['message' => 'Channel created successfully']);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Channels $channel)
    {

       $request->validate([
           'name' => 'required',
           'clientsCount' => 'required',
       ]);

        // check if the different channel with the same name already exists
        if ( Channels::where('name', $request->name)->where('id', '!=', $channel->id)->exists()) {
            return response()->json(['error' => 'Channel with the same name already exists'], 400);
        }

        // check if the clientsCount is greater than 0
        if ($request->clientsCount <= 0) {
            return response()->json(['error' => 'clientsCount must be greater than 0'], 400);
        }

        $channel->name = $request->name;
        $channel->clientsCount = $request->clientsCount;
        $channel->save();
        
        return response()->json(['message' => 'Channel updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Channels $channel)
    {

        if (!$channel) {
            return response()->json(['error' => 'Channel not found'], 404);
        }

        $channel->delete();
        return response()->json(['message' => 'Channel deleted successfully']);
    }
}
