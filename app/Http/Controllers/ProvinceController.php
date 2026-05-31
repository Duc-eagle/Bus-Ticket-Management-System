<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    public function index()
    {
        $provinces = Province::paginate(15);
        return view('admins.provinces.index', ['provinces' => $provinces]);
    }

    public function create()
    {
        return view('admins.provinces.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('provinces', 'public');
        }

        Province::create($data);
        return Redirect::route('provinces.index');
    }

    public function edit(Province $province)
    {
        return view('admins.provinces.edit', ['province' => $province]);
    }

    public function update(Request $request, Province $province)
    {
        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($province->image_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($province->image_path);
            }
            $data['image_path'] = $request->file('image')->store('provinces', 'public');
        }

        $province->update($data);
        return Redirect::route('provinces.index');
    }

    public function destroy(Province $province)
    {
        $province->delete();
        
        DB::statement('ALTER TABLE provinces AUTO_INCREMENT = 1;');
        
        return Redirect::route('provinces.index');
    }
}
