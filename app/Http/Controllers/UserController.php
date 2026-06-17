<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleType = $request->get('role_type', 'customer');

            if ($roleType === 'customer') {
                $users = User::where('role', 'customer')->paginate(10);
            } else {
                $users = User::whereIn('role', ['admin', 'staff'])->paginate(10);
            }

            // Appends query string so pagination links preserve 'role_type'
            $users->appends($request->query());

            return view('admins.users.partials.table', ['users' => $users]);
        }

        return view('admins.users.index');
    }

    public function customers()
    {
        $users = User::where('role', 'customer')->orderBy('id', 'desc')->paginate(10);
        return view('admins.users.list', ['users' => $users, 'title' => 'Danh Sách Khách Hàng']);
    }

    public function staff()
    {
        // Adjusting roles to match my DB (admin, staff) instead of admin, author from the prompt
        $users = User::whereIn('role', ['admin', 'staff'])->orderBy('id', 'desc')->paginate(10);
        return view('admins.users.list', ['users' => $users, 'title' => 'Danh Sách Nhân Viên']);
    }

    public function create()
    {
        return view('admins.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        User::create($data);
        return Redirect::route('users.index')->with('success', 'Thêm người dùng thành công');
    }

    public function edit(User $user)
    {
        return view('admins.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->all();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return Redirect::route('users.index')->with('success', 'Cập nhật thông tin thành công');
    }

    public function destroy(User $user)
    {
        $user->delete();

        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        return Redirect::route('users.index');
    }

    public function checkPhone(Request $request)
    {
        // 1. LẤY SỐ ĐIỆN THOẠI TỪ REQUEST QUERY
        $phone = $request->query('phone');
        
        // 2. KHỞI TẠO MẢNG JSON TRẢ VỀ MẶC ĐỊNH
        $responseData = [];
        $responseData['exists'] = false;
        $responseData['name'] = '';

        // 3. KIỂM TRA ĐẦU VÀO CƠ BẢN
        if (empty($phone)) {
            return response()->json($responseData);
        }

        // 4. TÌM KHÁCH HÀNG THEO SỐ ĐIỆN THOẠI
        $user = User::where('phone', $phone)->first();

        // 5. NẾU TÌM THẤY, CẬP NHẬT LẠI MẢNG KẾT QUẢ
        if ($user != null) {
            $responseData['exists'] = true;
            $responseData['name'] = $user->full_name;
        }

        // 6. TRẢ VỀ ĐỊNH DẠNG JSON CHO FRONTEND AJAX
        return response()->json($responseData);
    }

    public function adminsLogin() {
        return view('admins.login');
    }

    public function adminsLoginProcess(Request $request) {
        if(Auth::guard('admin')->attempt($request->only('email', 'password'), $request->has('remember'))) {
            $user = Auth::guard('admin')->user();
            if ($user->role == 'admin' || $user->role == 'staff' || $user->role == 'author') {
                $request->session()->regenerate();
                return Redirect::route('dashboard');
            } else {
                Auth::guard('admin')->logout();
                return Redirect::back()->with('error', 'Tài khoản không có quyền truy cập');
            }
        }
        return Redirect::back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    public function adminsLogout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::route('admins.login');
    }

    public function customerLogin() {
        return view('customer.login');
    }

    public function customerLoginProcess(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(Auth::guard('web')->attempt($request->only('email', 'password'), $request->has('remember'))) {
            $user = Auth::guard('web')->user();
            if ($user->role == 'customer') {
                $request->session()->regenerate();
                return Redirect::route('home');
            } else {
                Auth::guard('web')->logout();
                return Redirect::back()->with('error', 'Tài khoản này không phải khách hàng');
            }
        }

        return Redirect::back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    public function customerLogout(Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::route('home');
    }

    public function customerAccount()
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        return view('customer.account', [
            'user' => Auth::guard('web')->user(),
        ]);
    }

    public function changePasswordView()
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        return view('customer.change-password', [
            'user' => Auth::guard('web')->user(),
        ]);
    }

    public function changePasswordProcess(Request $request)
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        // Validate input
        $request->validate([
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::guard('web')->user()->password)) {
                        $fail('Mật khẩu hiện tại không đúng');
                    }
                },
            ],
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        // Update password
        Auth::guard('web')->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return Redirect::route('customer.change-password')->with('success', 'Đổi mật khẩu thành công!');
    }

    public function history()
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        $user = Auth::guard('web')->user();

        // Auto-cancel expired ZaloPay orders (older than 20 minutes)
        \App\Models\Ticket::where('user_id', $user->id)
            ->where('status', 'pending_payment')
            ->whereHas('paymentMethod', function ($query) {
                $query->where('method_name', 'like', '%ZaloPay%');
            })
            ->where('created_at', '<', now()->subMinutes(20))
            ->delete();

        $tickets = \App\Models\Ticket::with(['trip', 'trip.route.departureStation', 'trip.route.arrivalStation', 'seat', 'trip.bus', 'paymentMethod'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedTickets = $tickets->groupBy('ticket_name');

        $currentTickets = collect();
        $pastTickets = collect();
        $cancelledTickets = collect();

        foreach ($groupedTickets as $ticketName => $group) {
            $activeTix = $group->where('status', '!=', 'cancelled');
            $cancelledTix = $group->where('status', 'cancelled');

            $group->totalPrice = $activeTix->sum('total');
            $group->activeSeats = $activeTix->pluck('seat.seat_code')->filter()->join(', ');
            $group->cancelledSeats = $cancelledTix->pluck('seat.seat_code')->filter()->join(', ');

            $allCancelled = $activeTix->isEmpty();

            if ($allCancelled) {
                $cancelledTickets->put($ticketName, $group);
                continue;
            }

            // Automatic time check based on the first NON-CANCELLED ticket
            $firstTicket = $activeTix->first();
            $tripDate = $firstTicket->trip->trip_date;
            $departureTime = $firstTicket->trip->departure_time;

            // Handle edge case if relations are missing
            if (!$tripDate || !$departureTime) {
                $currentTickets->put($ticketName, $group);
                continue;
            }

            $departureDateTime = \Carbon\Carbon::parse($tripDate . ' ' . $departureTime);

            if ($departureDateTime->isPast()) {
                $pastTickets->put($ticketName, $group);
            } else {
                $currentTickets->put($ticketName, $group);
            }
        }

        return view('customer.history', [
            'user' => $user,
            'currentTickets' => $currentTickets,
            'pastTickets' => $pastTickets,
            'cancelledTickets' => $cancelledTickets
        ]);
    }

    public function show($id)
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        $user = Auth::guard('web')->user();
        $ticket = \App\Models\Ticket::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $ticketGroup = \App\Models\Ticket::with(['trip', 'trip.route.departureStation', 'trip.route.arrivalStation', 'seat', 'trip.bus', 'paymentMethod'])
            ->where('user_id', $user->id)
            ->where('ticket_name', $ticket->ticket_name)
            ->get();

        return view('customer.ticket_detail', [
            'user' => $user,
            'ticketGroup' => $ticketGroup
        ]);
    }

    public function cancel($id)
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->role !== 'customer') {
            return Redirect::route('customer.login');
        }

        $user = Auth::guard('web')->user();
        $ticket = \App\Models\Ticket::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if (in_array($ticket->status, ['pending_payment', 'paid', 'confirmed'])) {
            $ticket->status = 'cancelled';
            $ticket->save();
            return Redirect::back()->with('success', 'Đã hủy ghế thành công!');
        }

        return Redirect::back()->with('error', 'Không thể hủy vé này!');
    }
}
