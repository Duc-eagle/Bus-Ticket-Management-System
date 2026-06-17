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
        // 1. LẤY DỮ LIỆU TỪ FORM TÌM KIẾM
        $departure_id = $request->input('departure_id');
        $arrival_id = $request->input('arrival_id');
        $date = $request->input('date');

        // 2. KIỂM TRA ĐẦU VÀO CƠ BẢN (Không dùng $request->validate)
        if (empty($departure_id)) {
            return redirect()->back()->with('error', 'Vui lòng chọn điểm đi.');
        }
        if (empty($arrival_id)) {
            return redirect()->back()->with('error', 'Vui lòng chọn điểm đến.');
        }

        // 3. TÌM TUYẾN ĐƯỜNG PHÙ HỢP (Divide and Conquer - Bước 1)
        // Thay vì dùng hàm whereHas phức tạp lồng nhau, ta tách ra làm 2 bước rõ ràng.
        // Bước 1: Tìm tất cả các "Tuyến đường" (Route) thỏa mãn điểm đi và điểm đến.
        $routes = \App\Models\Route::where('departure_location', $departure_id)
                                   ->where('arrival_location', $arrival_id)
                                   ->get();

        // 4. LẤY DANH SÁCH ID CỦA CÁC TUYẾN ĐƯỜNG (Dùng vòng lặp foreach cơ bản)
        $routeIdsArray = [];
        foreach ($routes as $route) {
            $routeIdsArray[] = $route->id;
        }

        // 5. TÌM CÁC CHUYẾN ĐI DỰA TRÊN ID TUYẾN ĐƯỜNG (Divide and Conquer - Bước 2)
        // Lấy tất cả các "Chuyến đi" (Trip) thuộc về mảng ID tuyến đường vừa tìm được ở Bước 1.
        $query = \App\Models\Trip::with(['route.departureStation', 'route.arrivalStation', 'bus.images'])
                                 ->whereIn('route_id', $routeIdsArray);

        // Lọc trạng thái rõ ràng (Thay cho hàm whereNotIn)
        $query = $query->where('status', '!=', 'cancelled');
        $query = $query->where('status', '!=', 'running');

        // Lọc theo ngày đi nếu người dùng có chọn ngày
        if (!empty($date)) {
            $query = $query->whereDate('trip_date', $date);
        }

        // Đếm số ghế đã được đặt (đã thanh toán hoặc đang chờ)
        $trips = $query->withCount(['tickets as booked_seats' => function($q) {
            $q->whereIn('status', ['confirmed', 'paid', 'pending_payment']);
        }])->orderBy('trip_date')->orderBy('departure_time')->get();

        // 6. TÍNH TOÁN SỐ GHẾ TRỐNG THỦ CÔNG BẰNG VÒNG LẶP
        foreach ($trips as $trip) {
            $totalSeats = $trip->bus->total_seats;
            $bookedSeats = $trip->booked_seats;
            
            $availableSeats = $totalSeats - $bookedSeats;
            
            // Đảm bảo số ghế trống không bị âm
            if ($availableSeats < 0) {
                $availableSeats = 0;
            }
            
            $trip->available_seats = $availableSeats;
        }

        // Lấy danh sách trạm để hiển thị trên bộ lọc giao diện
        $stations = \App\Models\BusStation::all();

        // Đẩy dữ liệu ra view
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
            ->with('bus.images')
            ->withCount(['tickets as booked_seats' => function($q) {
                $q->whereIn('status', ['confirmed', 'paid', 'pending_payment']);
            }])
            ->whereNotIn('status', ['cancelled', 'running'])
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
