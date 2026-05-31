<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SeatController extends Controller
{
    public function index()
    {
        $buses = Bus::with('seats')->get();
        return view('admins.seats.index', ['buses' => $buses]);
    }

    public function showByBus($id)
    {
        $bus = Bus::findOrFail($id);
        $seats = Seat::where('bus_id', $id)
                     ->orderByRaw('SUBSTRING(seat_code, 1, 1) ASC, LENGTH(seat_code) ASC, seat_code ASC')
                     ->paginate(10);
        return view('admins.seats.by_bus', ['bus' => $bus, 'seats' => $seats]);
    }

    public function create()
    {
        $buses = Bus::all();
        return view('admins.seats.create', ['buses' => $buses]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'seat_code' => [
                'required',
                'string',
                Rule::unique('seats')->where(function ($query) use ($request) {
                    return $query->where('bus_id', $request->bus_id);
                })
            ],
            'status' => 'required|in:available,booked',
        ], [
            'seat_code.unique' => 'Mã ghế này đã tồn tại trên chuyến xe này. Vui lòng chọn mã khác.',
        ]);

        Seat::create($request->all());
        return Redirect::route('seats.index')->with('success', 'Thêm ghế thành công!');
    }

    public function edit(Seat $seat)
    {
        $buses = Bus::all();
        return view('admins.seats.edit', ['seat' => $seat, 'buses' => $buses]);
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'seat_code' => [
                'required',
                'string',
                Rule::unique('seats')->where(function ($query) use ($request) {
                    return $query->where('bus_id', $request->bus_id);
                })->ignore($seat->id)
            ],
            'status' => 'required|in:available,booked',
        ], [
            'seat_code.unique' => 'Mã ghế này đã tồn tại trên chuyến xe này. Vui lòng chọn mã khác.',
        ]);

        $seat->update($request->all());
        return Redirect::route('seats.index')->with('success', 'Cập nhật ghế thành công!');
    }

    public function destroy(Seat $seat)
    {
        $seat->delete();

        DB::statement('ALTER TABLE seats AUTO_INCREMENT = 1;');

        return Redirect::route('seats.index');
    }
}
