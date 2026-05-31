<?php

namespace App\Http\Controllers;

use App\Models\BusStation;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BusStationController extends Controller
{
    public function index()
    {
        $busStations = BusStation::with('province')->paginate(15);
        return view('admins.bus_stations.index', ['busStations' => $busStations]);
    }

    public function create()
    {
        $provinces = Province::all();
        return view('admins.bus_stations.create', ['provinces' => $provinces]);
    }

    public function store(Request $request)
    {


        BusStation::create($request->all());
        return Redirect::route('bus_stations.index');
    }

    public function edit(BusStation $busStation)
    {
        $provinces = Province::all();
        return view('admins.bus_stations.edit', ['busStation' => $busStation, 'provinces' => $provinces]);
    }

    public function update(Request $request, BusStation $busStation)
    {


        $busStation->update($request->all());
        return Redirect::route('bus_stations.index');
    }

    public function destroy(BusStation $busStation)
    {
        $busStation->delete();
        
        DB::statement('ALTER TABLE bus_stations AUTO_INCREMENT = 1;');
        
        return Redirect::route('bus_stations.index');
    }
}
