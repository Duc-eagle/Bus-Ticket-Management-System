<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TripController extends Controller
{
    public function getSeats(Trip $trip)
    {
        $seats = \App\Models\Seat::where('bus_id', $trip->bus_id)->get();
        $bookedSeatIds = \App\Models\Ticket::where('trip_id', $trip->id)
            ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
            ->pluck('seat_id');

        return response()->json([
            'seats' => $seats,
            'booked_seat_ids' => $bookedSeatIds
        ]);
    }
    public function index()
    {
        $trips = Trip::with('bus', 'route')->paginate(15);
        return view('admins.trips.index', ['trips' => $trips]);
    }

    public function create()
    {
        $buses = Bus::all();
        $routes = Route::all();
        return view('admins.trips.create', ['buses' => $buses, 'routes' => $routes]);
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'trip_date' => 'required|date|after_or_equal:' . Carbon::today()->toDateString(),
        ], [
            'trip_date.after_or_equal' => 'Ngày đi phải từ hôm nay trở đi',
        ]);

        Trip::create($request->all());
        return Redirect::route('trips.index');
    }

    public function edit(Trip $trip)
    {
        $buses = Bus::all();
        $routes = Route::all();
        return view('admins.trips.edit', ['trip' => $trip, 'buses' => $buses, 'routes' => $routes]);
    }

    public function update(Request $request, Trip $trip)
    {
        // Validate input
        $request->validate([
            'trip_date' => 'required|date',
        ]);

        $trip->update($request->all());
        return Redirect::route('trips.index');
    }

    public function destroy(Trip $trip)
    {
        $trip->delete();
        
        DB::statement('ALTER TABLE trips AUTO_INCREMENT = 1;');
        
        return Redirect::route('trips.index');
    }
}
