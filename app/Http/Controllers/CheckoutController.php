<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Seat;
use App\Models\PaymentMethod;

class CheckoutController extends Controller
{
    // ZaloPay Sandbox Credentials
    private $zaloAppId = "2553";
    private $zaloKey1 = "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL";
    private $zaloKey2 = "kLtgPl8YESYV3I5K2WN2R29y8KJWHoaF";
    private $zaloEndpoint = "https://sb-openapi.zalopay.vn/v2/create";
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
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_ids' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id'
        ]);

        $seatIds = explode(',', $request->seat_ids);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Lock the seats for this trip to prevent race conditions
            $bookedSeats = \App\Models\Ticket::where('trip_id', $request->trip_id)
                ->whereIn('seat_id', $seatIds)
                ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
                ->lockForUpdate()
                ->count();

            if ($bookedSeats > 0) {
                \Illuminate\Support\Facades\DB::rollBack();
                return redirect()->route('home')->with('error', 'Rất tiếc, ghế bạn chọn vừa có người đặt.');
            }

            $trip = Trip::findOrFail($request->trip_id);
            $userId = \Illuminate\Support\Facades\Auth::id();

            // Generate ONE shared ticket code before the loop
            do {
                $sharedTicketCode = 'MB-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6));
                $exists = \App\Models\Ticket::where('ticket_name', $sharedTicketCode)->exists();
            } while ($exists);

            foreach ($seatIds as $seatId) {
                \App\Models\Ticket::create([
                    'user_id' => $userId,
                    'trip_id' => $trip->id,
                    'seat_id' => $seatId,
                    'payment_method_id' => $request->payment_method_id,
                    'total' => $trip->base_price, // Store price of one seat to avoid revenue inflation
                    'status' => 'pending_payment', // Start as pending until payment is confirmed
                    'purchase_date' => now(),
                    'ticket_name' => $sharedTicketCode // Shared Code
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);
            $totalAmount = count($seatIds) * $trip->base_price;

            // ZALOPAY INTEGRATION
            if (strtolower($paymentMethod->method_name) == 'zalopay') {
                $app_trans_id = date("ymd") . '_' . $sharedTicketCode;
                
                $app_time = round(microtime(true) * 1000);
                $app_trans_id = date("ymd") . "_" . $sharedTicketCode;
                $amount = (int) $totalAmount;
                $app_user = "user_" . $userId;
                $embed_data = json_encode(['redirecturl' => route('payment.zalopay_return')]);
                $item = json_encode([['itemid' => 'ticket', 'itemname' => 'Bus Ticket', 'itemprice' => $amount, 'itemquantity' => 1]]);
                $description = "Thanh toan ve xe MyBus - Ma: $sharedTicketCode";

                // Calculate HMAC SHA256 Signature for Security
                // mac = HMAC(app_id + "|" + app_trans_id + "|" + app_user + "|" + amount + "|" + app_time + "|" + embed_data + "|" + item)
                $dataToHash = $this->zaloAppId . "|" . $app_trans_id . "|" . $app_user . "|" . $amount . "|" . $app_time . "|" . $embed_data . "|" . $item;
                $mac = hash_hmac("sha256", $dataToHash, $this->zaloKey1);

                $order = [
                    "app_id" => $this->zaloAppId,
                    "app_time" => $app_time,
                    "app_trans_id" => $app_trans_id,
                    "app_user" => $app_user,
                    "item" => $item,
                    "embed_data" => $embed_data,
                    "amount" => $amount,
                    "description" => $description,
                    "bank_code" => "",
                    "mac" => $mac
                ];

                // Send POST Request to ZaloPay
                $response = \Illuminate\Support\Facades\Http::asForm()->post($this->zaloEndpoint, $order);
                $result = $response->json();

                if (!isset($result['order_url'])) {
                    dd('ZaloPay API Error:', $result);
                }

                if (isset($result['return_code']) && $result['return_code'] == 1 && isset($result['order_url'])) {
                    return redirect($result['order_url']);
                }

                return back()->with('error', 'Lỗi khởi tạo ZaloPay: ' . ($result['return_message'] ?? 'Unknown error'));
            }

            // If not ZaloPay, mark as paid (for Cash/Transfer)
            \App\Models\Ticket::where('ticket_name', $sharedTicketCode)->update(['status' => 'paid']);

            return redirect()->route('customer.booking_success', [
                'seat_ids' => $request->seat_ids,
                'total' => $totalAmount
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi trong quá trình thanh toán: ' . $e->getMessage());
        }
    }

    public function retryPayment($ticket_name)
    {
        $tickets = \App\Models\Ticket::where('ticket_name', $ticket_name)
            ->where('user_id', auth()->id())
            ->where('status', 'pending_payment')
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('customer.history')->with('error', 'Không tìm thấy đơn hàng cần thanh toán hoặc đơn hàng đã được xử lý.');
        }

        $firstTicket = $tickets->first();
        
        // Double check expiration
        if ($firstTicket->created_at->diffInMinutes(now()) > 20) {
            \App\Models\Ticket::where('ticket_name', $ticket_name)
                ->where('user_id', auth()->id())
                ->update(['status' => 'cancelled']);
            return redirect()->route('customer.history')->with('error', 'Đơn hàng đã hết hạn thanh toán (quá 20 phút).');
        }

        $totalAmount = $tickets->sum('total');
        $userId = auth()->id();

        // Build ZaloPay Order
        $app_time = round(microtime(true) * 1000);
        $app_trans_id = date("ymd") . "_" . $ticket_name . "_" . time();
        $amount = (int) $totalAmount;
        $app_user = "user_" . $userId;
        $embed_data = json_encode(['redirecturl' => route('payment.zalopay_return')]);
        $item = json_encode([['itemid' => 'ticket', 'itemname' => 'Bus Ticket', 'itemprice' => $amount, 'itemquantity' => 1]]);
        $description = "Thanh toan lai ve xe MyBus - Ma: $ticket_name";

        $dataToHash = $this->zaloAppId . "|" . $app_trans_id . "|" . $app_user . "|" . $amount . "|" . $app_time . "|" . $embed_data . "|" . $item;
        $mac = hash_hmac("sha256", $dataToHash, $this->zaloKey1);

        $order = [
            "app_id" => $this->zaloAppId,
            "app_time" => $app_time,
            "app_trans_id" => $app_trans_id,
            "app_user" => $app_user,
            "item" => $item,
            "embed_data" => $embed_data,
            "amount" => $amount,
            "description" => $description,
            "bank_code" => "",
            "mac" => $mac
        ];

        $response = \Illuminate\Support\Facades\Http::asForm()->post($this->zaloEndpoint, $order);
        $result = $response->json();

        if (isset($result['return_code']) && $result['return_code'] == 1 && isset($result['order_url'])) {
            return redirect($result['order_url']);
        }

        return back()->with('error', 'Lỗi khởi tạo thanh toán ZaloPay: ' . ($result['return_message'] ?? 'Unknown error'));
    }

    public function success(Request $request)
    {
        return view('customer.booking_success', [
            'seatIds' => $request->query('seat_ids'),
            'total' => $request->query('total')
        ]);
    }

    /**
     * ZaloPay Return Callback
     */
    public function zaloPayReturn(Request $request)
    {
        $status = $request->query('status');
        $apptransid = $request->query('apptransid'); // Format: yyMMdd_MB-XXXXXX
        
        // Check if payment was successful (status == 1)
        if ($status == 1 && $apptransid) {
            // Extract the shared ticket code
            $parts = explode('_', $apptransid);
            if (count($parts) >= 2) {
                $sharedTicketCode = $parts[1];
                
                // Update all seats in this order to paid
                \App\Models\Ticket::where('ticket_name', $sharedTicketCode)->update(['status' => 'paid']);
                
                return redirect()->route('customer.history')->with('success', 'Thanh toán qua ZaloPay thành công! Mã vé: ' . $sharedTicketCode);
            }
        }
        
        return redirect()->route('customer.history')->with('error', 'Thanh toán ZaloPay thất bại hoặc đã bị hủy.');
    }
}
