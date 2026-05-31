<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\BusStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RouteController extends Controller
{
    public function homepage()
    {
        $routes = Route::with('departureStation', 'arrivalStation', 'trips')->limit(6)->get();
        $stations = BusStation::all();
        $provinces = \App\Models\Province::limit(4)->get();
        return view('customer.index', ['routes' => $routes, 'stations' => $stations, 'provinces' => $provinces]);
    }

    public function search(Request $request)
    {
        $departure_id = $request->get('departure_id');
        $arrival_id = $request->get('arrival_id');
        $date = $request->get('date');

        $query = \App\Models\Trip::with(['route.departureStation', 'route.arrivalStation', 'bus'])
            ->where('status', '!=', 'cancelled');


        //Sang loc tuyen duong
        if ($departure_id || $arrival_id) {
            $query->whereHas('route', function($q) use ($departure_id, $arrival_id) {
                if ($departure_id) $q->where('departure_location', $departure_id);
                if ($arrival_id) $q->where('arrival_location', $arrival_id);
            });
        }
        //Sang loc ngay gio
        if ($date) {
            $query->whereDate('trip_date', $date);
        }
        //Thong ke ve ban va xuat du lieu
        $trips = $query->withCount(['tickets as booked_seats' => function($q) {
            $q->whereIn('status', ['confirmed', 'paid', 'pending_payment']);
        }])->orderBy('trip_date')->orderBy('departure_time')->get();
        //Tinh so ghe trong
        foreach($trips as $trip) {
            $trip->available_seats = max(0, $trip->bus->total_seats - $trip->booked_seats);
        }
        //Day ra giao dien
        $stations = BusStation::all();

        return view('customer.search_results', [
            'trips' => $trips,
            'stations' => $stations,
            'request' => $request
        ]);
    }

    public function customerTrips(Route $route)
    {
        $today = Carbon::today();
        $endDate = $today->copy()->addDays(6);

        $route->load('departureStation', 'arrivalStation');
        $trips = $route->trips()
            ->with('bus')
            ->withCount(['tickets as booked_seats' => function($q) {
                $q->whereIn('status', ['confirmed', 'paid', 'pending_payment']);
            }])
            ->whereBetween('trip_date', [$today->toDateString(), $endDate->toDateString()])
            ->orderBy('trip_date')
            ->orderBy('departure_time')
            ->get();

        foreach($trips as $trip) {
            $trip->available_seats = max(0, $trip->bus->total_seats - $trip->booked_seats);
        }

        return view('customer.route_trips', [
            'route' => $route,
            'trips' => $trips,
            'today' => $today,
            'endDate' => $endDate,
        ]);
    }

    public function index()
    {
        $routes = Route::with('departureStation', 'arrivalStation')->paginate(15);
        return view('admins.routes.index', ['routes' => $routes]);
    }

    public function create()
    {
        $busStations = BusStation::all();
        return view('admins.routes.create', ['busStations' => $busStations]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('routes', 'public');
        }

        Route::create($data);
        return Redirect::route('routes.index');
    }

    public function edit(Route $route)
    {
        $busStations = BusStation::all();
        return view('admins.routes.edit', ['route' => $route, 'busStations' => $busStations]);
    }

    public function update(Request $request, Route $route)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($route->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($route->image_path);
            }
            $data['image_path'] = $request->file('image')->store('routes', 'public');
        }

        $route->update($data);
        return Redirect::route('routes.index');
    }

    public function destroy(Route $route)
    {
        $route->delete();

        DB::statement('ALTER TABLE routes AUTO_INCREMENT = 1;');

        return Redirect::route('routes.index');
    }

}
