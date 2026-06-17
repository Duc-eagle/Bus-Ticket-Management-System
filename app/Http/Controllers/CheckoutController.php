<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Seat;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'selected_seats' => 'required|string'
        ]);

        $trip = Trip::with(['route.departureStation', 'route.arrivalStation', 'bus'])->findOrFail($request->trip_id);

        $seatIds = explode(',', $request->selected_seats);
        $seats = Seat::whereIn('id', $seatIds)->where('bus_id', $trip->bus_id)->get();

        if ($seats->isEmpty()) {
            return back()->with('error', 'Vui lòng chọn ghế hợp lệ.');
        }

        $totalPrice = $seats->count() * $trip->base_price;
        $paymentMethods = PaymentMethod::all();

        return view('customer.checkout', [
            'trip' => $trip,
            'seats' => $seats,
            'totalPrice' => $totalPrice,
            'paymentMethods' => $paymentMethods
        ]);
    }

    public function processCheckout(Request $request)
    {
        // 1. LẤY DỮ LIỆU TỪ FORM (Không dùng $request->validate)
        $trip_id = $request->input('trip_id');
        $seat_ids_string = $request->input('seat_ids');
        $payment_method_id = $request->input('payment_method_id');

        // 2. KIỂM TRA DỮ LIỆU ĐẦU VÀO (Validation thủ công)
        if (empty($trip_id)) {
            return back()->with('error', 'Thiếu thông tin chuyến đi.');
        }
        if (empty($seat_ids_string)) {
            return back()->with('error', 'Bạn chưa chọn ghế nào.');
        }
        if (empty($payment_method_id)) {
            return back()->with('error', 'Vui lòng chọn phương thức thanh toán.');
        }

        // Tách chuỗi ghế thành mảng để dễ xử lý
        $seatIdsArray = explode(',', $seat_ids_string);

        // 3. BẮT ĐẦU TRANSACTION (Bảo vệ dữ liệu)
        // Tại sao phải dùng Transaction? Để nếu có lỗi giữa chừng (ví dụ tạo vé 1 thành công nhưng vé 2 lỗi),
        // hệ thống sẽ tự động hủy (Rollback) tất cả, không lưu dữ liệu rác vào database.
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // 4. KIỂM TRA GHẾ TRỐNG (Kỹ thuật Pessimistic Locking)
            // Tại sao phải dùng lockForUpdate()? 
            // Đề phòng trường hợp 2 khách hàng cùng click thanh toán 1 ghế ở cùng 1 phần nghìn giây.
            // Hệ thống sẽ khóa dòng dữ liệu ghế này lại cho đến khi Transaction kết thúc.
            $bookedSeatsCount = \App\Models\Ticket::where('trip_id', $trip_id)
                ->whereIn('seat_id', $seatIdsArray)
                ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
                ->lockForUpdate() // Khóa dòng
                ->count();

            // Nếu phát hiện ghế đã có người đặt, hủy Transaction và báo lỗi ngay lập tức
            if ($bookedSeatsCount > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->route('home')->with('error', 'Rất tiếc, ghế bạn chọn vừa có người đặt.');
            }

            // 5. TÌM CHUYẾN ĐI VÀ NGƯỜI DÙNG
            $trip = Trip::find($trip_id);
            if ($trip == null) {
                \Illuminate\Support\Facades\DB::rollBack();
                return back()->with('error', 'Không tìm thấy chuyến đi này trong hệ thống.');
            }
            
            $userId = \Illuminate\Support\Facades\Auth::id();

            // 6. TẠO MÃ VÉ CHUNG (Shared Ticket Code)
            // Dùng vòng lặp while để đảm bảo mã vé được tạo ra là duy nhất, không bao giờ trùng lặp
            $isUnique = false;
            $sharedTicketCode = '';
            
            while ($isUnique == false) {
                $randomString = \Illuminate\Support\Str::random(6);
                $sharedTicketCode = 'MB-' . strtoupper($randomString);
                
                $checkExists = \App\Models\Ticket::where('ticket_name', $sharedTicketCode)->first();
                if ($checkExists == null) {
                    $isUnique = true; // Thoát vòng lặp nếu mã chưa ai dùng
                }
            }

            // 7. TẠO VÉ CHO TỪNG GHẾ (Vòng lặp cơ bản)
            foreach ($seatIdsArray as $seatId) {
                $newTicket = new \App\Models\Ticket();
                $newTicket->user_id = $userId;
                $newTicket->trip_id = $trip->id;
                $newTicket->seat_id = $seatId;
                $newTicket->payment_method_id = $payment_method_id;
                $newTicket->total = $trip->base_price;
                $newTicket->status = 'pending_payment'; // Trạng thái mặc định ban đầu
                $newTicket->purchase_date = now();
                $newTicket->ticket_name = $sharedTicketCode;
                $newTicket->save();
            }

            // 8. KẾT THÚC TRANSACTION (Lưu dữ liệu vào DB)
            \Illuminate\Support\Facades\DB::commit();

            // 9. XỬ LÝ THANH TOÁN THÀNH CÔNG VÀ CHUYỂN HƯỚNG
            $totalAmount = count($seatIdsArray) * $trip->base_price;

            // Vì đã mock logic thanh toán cho bài bảo vệ, ta cập nhật trạng thái vé thành 'paid' trực tiếp
            \App\Models\Ticket::where('ticket_name', $sharedTicketCode)->update(['status' => 'paid']);

            // Chuyển hướng về trang thành công
            return redirect()->route('customer.booking_success', [
                'seat_ids' => $seat_ids_string,
                'total' => $totalAmount
            ]);

        } catch (\Exception $e) {
            // Nếu có bất kỳ lỗi code nào xảy ra trong khối try, Rollback toàn bộ để an toàn
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi trong quá trình thanh toán: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        return view('customer.booking_success', [
            'seatIds' => $request->query('seat_ids'),
            'total' => $request->query('total')
        ]);
    }
}
