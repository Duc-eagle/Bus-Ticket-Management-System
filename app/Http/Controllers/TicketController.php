<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Seat;
use App\Models\Trip;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user', 'seat', 'trip', 'paymentMethod');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('ticket_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('phone', 'like', "%{$search}%");
                  });
        }

        if ($request->has('filter_date')) {
            $query->whereDate('purchase_date', $request->filter_date);
        }

        if ($request->has('filter_month')) {
            $query->whereMonth('purchase_date', $request->filter_month)
                  ->whereYear('purchase_date', now()->year);
        }

        $paginatedTicketNames = $query->select('ticket_name', DB::raw('MAX(created_at) as max_created_at'))
            ->groupBy('ticket_name')
            ->orderBy('max_created_at', 'desc')
            ->paginate(10);

        $ticketNames = $paginatedTicketNames->pluck('ticket_name');

        $tickets = Ticket::with('user', 'seat', 'trip', 'paymentMethod')
            ->whereIn('ticket_name', $ticketNames)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('ticket_name');

        // Khởi tạo một Collection mới để chứa dữ liệu đã được xử lý đơn giản
        $transformedItems = collect();

        // Lặp qua từng nhóm vé (ticket_name)
        foreach ($paginatedTicketNames as $ptn) {
            // Lấy ra danh sách các vé thuộc về mã đặt chỗ này
            $group = $tickets->get($ptn->ticket_name);
            
            if ($group) {
                // Khởi tạo các biến cơ bản thay vì dùng các hàm Collection phức tạp
                $activeTicketsCount = 0;
                $cancelledTicketsCount = 0;
                $totalPrice = 0;
                $activeSeatsArray = [];
                $cancelledSeatsArray = [];
                $firstActiveStatus = null;

                // Vòng lặp foreach đơn giản để kiểm tra từng vé trong nhóm
                foreach ($group as $ticket) {
                    if ($ticket->status != 'cancelled') {
                        // Nếu vé chưa hủy
                        $activeTicketsCount = $activeTicketsCount + 1;
                        $totalPrice = $totalPrice + $ticket->total;
                        
                        if ($ticket->seat) {
                            $activeSeatsArray[] = $ticket->seat->seat_code;
                        }

                        // Lấy trạng thái của vé active đầu tiên để hiển thị
                        if ($firstActiveStatus == null) {
                            $firstActiveStatus = $ticket->status;
                        }
                    } else {
                        // Nếu vé đã hủy
                        $cancelledTicketsCount = $cancelledTicketsCount + 1;
                        
                        if ($ticket->seat) {
                            $cancelledSeatsArray[] = $ticket->seat->seat_code;
                        }
                    }
                }

                // Kiểm tra xem tất cả vé đã bị hủy hay chưa bằng if/else (Thay cho toán tử 3 ngôi)
                if ($activeTicketsCount == 0) {
                    $group->allCancelled = true;
                    $group->displayStatus = 'cancelled';
                } else {
                    $group->allCancelled = false;
                    $group->displayStatus = $firstActiveStatus;
                }

                // Gán các giá trị đã tính toán thủ công vào nhóm
                $group->totalPrice = $totalPrice;
                $group->activeSeats = implode(', ', $activeSeatsArray);
                $group->cancelledSeats = implode(', ', $cancelledSeatsArray);

                // Kiểm tra xem có phải là hủy một phần (có vé active VÀ có vé hủy)
                if ($activeTicketsCount > 0 && $cancelledTicketsCount > 0) {
                    $group->isPartialCancel = true;
                } else {
                    $group->isPartialCancel = false;
                }

                // Thêm nhóm đã xử lý vào mảng kết quả
                $transformedItems->put($ptn->ticket_name, $group);
            }
        }

        $paginatedTicketNames->setCollection($transformedItems);
        $groupedTickets = $paginatedTicketNames;

        return view('admins.tickets.index', ['groupedTickets' => $groupedTickets]);
    }

    public function create()
    {
        $seats = Seat::all();
        $trips = Trip::all();
        $paymentMethods = PaymentMethod::all();
        return view('admins.tickets.create', ['seats' => $seats, 'trips' => $trips, 'paymentMethods' => $paymentMethods]);
    }

    public function store(Request $request)
    {
        // 1. LẤY DỮ LIỆU TỪ FORM (Thay thế $request->validate)
        $customer_phone = $request->input('customer_phone');
        $customer_name = $request->input('customer_name');
        $trip_id = $request->input('trip_id');
        $seat_id = $request->input('seat_id');
        $payment_method_id = $request->input('payment_method_id');
        $status = $request->input('status');
        $purchase_date = $request->input('purchase_date');

        // 2. KIỂM TRA ĐẦU VÀO CƠ BẢN (Manual Validation)
        if (empty($customer_phone)) {
            return redirect()->back()->with('error', 'Vui lòng nhập số điện thoại khách hàng.');
        }
        if (empty($customer_name)) {
            return redirect()->back()->with('error', 'Vui lòng nhập tên khách hàng.');
        }
        if (empty($trip_id) || empty($seat_id)) {
            return redirect()->back()->with('error', 'Vui lòng chọn chuyến đi và ghế.');
        }

        try {
            DB::beginTransaction();

            // 3. TẠO HOẶC LẤY THÔNG TIN KHÁCH HÀNG
            // Cách làm thủ công, tường minh thay vì dùng hàm firstOrCreate của Laravel
            $user = User::where('phone', $customer_phone)->first();
            
            if ($user == null) {
                // Nếu khách hàng chưa tồn tại (số điện thoại mới), tạo tài khoản mới
                $user = new User();
                $user->phone = $customer_phone;
                $user->full_name = $customer_name;
                $user->user_name = $customer_phone; // Lấy SĐT làm username
                $user->email = $customer_phone . '@mybus.com'; // Tạo email ảo
                $user->password = bcrypt('12345678'); // Mật khẩu mặc định
                $user->role = 'customer';
                $user->save();
            }

            // 4. KIỂM TRA GHẾ TRỐNG (Kỹ thuật Pessimistic Locking)
            // Khoá dòng để tránh trùng lặp nếu có quản trị viên khác cũng đang thao tác
            $bookedSeatCount = Ticket::where('trip_id', $trip_id)
                ->where('seat_id', $seat_id)
                ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
                ->lockForUpdate()
                ->count();

            // Nếu số lượng vé tìm thấy > 0, tức là ghế đã bị đặt
            if ($bookedSeatCount > 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Lỗi: Ghế này vừa được khách hàng khác đặt mua. Vui lòng chọn ghế khác.');
            }

            // 5. TẠO MÃ VÉ DUY NHẤT (Vòng lặp while cơ bản)
            $isUnique = false;
            $ticketCode = '';
            
            while ($isUnique == false) {
                $randomString = \Illuminate\Support\Str::random(6);
                $ticketCode = 'MB-' . strtoupper($randomString);
                
                $checkExists = Ticket::where('ticket_name', $ticketCode)->first();
                if ($checkExists == null) {
                    $isUnique = true; // Thoát vòng lặp
                }
            }

            // 6. TÌM CHUYẾN ĐI ĐỂ LẤY GIÁ VÉ
            $trip = Trip::find($trip_id);
            if ($trip == null) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Lỗi: Không tìm thấy chuyến đi.');
            }

            // 7. LƯU VÉ MỚI VÀO CƠ SỞ DỮ LIỆU
            $newTicket = new Ticket();
            $newTicket->ticket_name = $ticketCode;
            $newTicket->user_id = $user->id;
            $newTicket->trip_id = $trip_id;
            $newTicket->seat_id = $seat_id;
            $newTicket->payment_method_id = $payment_method_id;
            $newTicket->total = $trip->base_price;
            $newTicket->status = $status;
            $newTicket->purchase_date = $purchase_date;
            $newTicket->save();

            // Lưu thay đổi vào Database
            DB::commit();
            return Redirect::route('tickets.index')->with('success', 'Tạo vé thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }



    public function edit(Ticket $ticket)
    {
        $users = User::all();
        $seats = Seat::all();
        $trips = Trip::all();
        $paymentMethods = PaymentMethod::all();
        return view('admins.tickets.edit', ['ticket' => $ticket, 'users' => $users, 'seats' => $seats, 'trips' => $trips, 'paymentMethods' => $paymentMethods]);
    }

    public function update(Request $request, Ticket $ticket)
    {


        $ticket->update($request->all());
        return Redirect::route('tickets.index');
    }

    public function show($ticket_name)
    {
        $tickets = Ticket::with(['user', 'seat', 'trip.route', 'paymentMethod'])
            ->where('ticket_name', $ticket_name)
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('tickets.index')->with('error', 'Không tìm thấy nhóm vé này.');
        }

        return view('admins.tickets.show', [
            'tickets' => $tickets,
            'ticket_name' => $ticket_name
        ]);
    }

    public function cancelSeat($id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Đã hủy ghế thành công!');
    }

    public function updateStatus(Request $request, $ticketName)
    {
        $request->validate([
            'status' => 'required|in:pending_payment,paid,cancelled'
        ]);

        Ticket::where('ticket_name', $ticketName)->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái vé thành công!');
    }

    public function destroy($ticketName)
    {
        Ticket::where('ticket_name', $ticketName)->delete();

        DB::statement('ALTER TABLE tickets AUTO_INCREMENT = 1;');

        return Redirect::route('tickets.index');
    }
}
