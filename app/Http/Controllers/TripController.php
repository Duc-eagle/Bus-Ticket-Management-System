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
    public function getSeats($id)
    {
        // 1. TÌM CHUYẾN ĐI (Không dùng Route Model Binding / findOrFail để tự bắt lỗi)
        $trip = \App\Models\Trip::find($id);
        
        // 2. KIỂM TRA SỰ TỒN TẠI CỦA CHUYẾN ĐI
        if ($trip == null) {
            $errorResponse = [];
            $errorResponse['success'] = false;
            $errorResponse['message'] = 'Không tìm thấy chuyến đi này.';
            return response()->json($errorResponse);
        }

        // 3. LẤY TẤT CẢ GHẾ CỦA CHIẾC XE CHẠY CHUYẾN NÀY
        $seats = \App\Models\Seat::where('bus_id', $trip->bus_id)->get();

        // 4. LẤY DANH SÁCH VÉ ĐÃ CÓ NGƯỜI ĐẶT
        $bookedTickets = \App\Models\Ticket::where('trip_id', $trip->id)
            ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
            ->get();

        // 5. NHẶT RA MÃ ID CỦA CÁC GHẾ ĐÃ ĐẶT (Bằng vòng lặp cơ bản, không dùng hàm pluck)
        $bookedSeatIdsArray = [];
        foreach ($bookedTickets as $ticket) {
            $bookedSeatIdsArray[] = $ticket->seat_id;
        }

        // 6. GÁN TỪNG DỮ LIỆU VÀO MẢNG RESPONSE TRƯỚC KHI TRẢ VỀ
        $responseData = [];
        $responseData['success'] = true;
        $responseData['seats'] = $seats;
        $responseData['booked_seat_ids'] = $bookedSeatIdsArray;

        // 7. XUẤT RA CHUẨN JSON
        return response()->json($responseData);
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
