<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::paginate(15);
        return view('admins.buses.index', ['buses' => $buses]);
    }

    public function create()
    {
        return view('admins.buses.create');
    }

    public function store(Request $request)
    {
        $bus = Bus::create($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('buses', 'public');
                \App\Models\BusImage::create([
                    'bus_id' => $bus->id,
                    'image_path' => $path
                ]);
            }
        }

        return Redirect::route('buses.index');
    }

    public function edit(Bus $bus)
    {
        return view('admins.buses.edit', ['bus' => $bus]);
    }

    public function update(Request $request, Bus $bus)
    {
        $bus->update($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('buses', 'public');
                \App\Models\BusImage::create([
                    'bus_id' => $bus->id,
                    'image_path' => $path
                ]);
            }
        }

        return Redirect::route('buses.index');
    }

    public function destroy(Bus $bus)
    {
        $bus->delete();
        
        DB::statement('ALTER TABLE buses AUTO_INCREMENT = 1;');
        
        return Redirect::route('buses.index');
    }
}
