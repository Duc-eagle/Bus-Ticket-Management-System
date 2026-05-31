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

        $groupedTickets = $query->orderBy('created_at', 'desc')->get()->groupBy('ticket_name');

        $groupedTickets->transform(function ($group) {
            $activeTickets = $group->where('status', '!=', 'cancelled');
            $cancelledTickets = $group->where('status', 'cancelled');
            
            $group->allCancelled = $activeTickets->isEmpty();
            $group->displayStatus = $group->allCancelled ? 'cancelled' : $activeTickets->first()->status;
            $group->totalPrice = $activeTickets->sum('total');
            $group->activeSeats = $activeTickets->pluck('seat.seat_code')->filter()->join(', ');
            $group->cancelledSeats = $cancelledTickets->pluck('seat.seat_code')->filter()->join(', ');
            $group->isPartialCancel = $activeTickets->isNotEmpty() && $cancelledTickets->isNotEmpty();
            
            return $group;
        });

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
        $request->validate([
            'customer_phone' => 'required|string|max:15',
            'customer_name' => 'required|string|max:255',
            'trip_id' => 'required|exists:trips,id',
            'seat_id' => 'required|exists:seats,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'status' => 'required|in:pending_payment,paid,cancelled',
            'purchase_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = User::firstOrCreate(
                ['phone' => $request->customer_phone],
                [
                    'full_name' => $request->customer_name,
                    'email' => $request->customer_phone . '@mybus.com',
                    'password' => bcrypt('12345678'),
                    'role' => 'customer'
                ]
            );

            // 1. Concurrency Check: Lock the specific seat for this trip
            $isBooked = Ticket::where('trip_id', $request->trip_id)
                ->where('seat_id', $request->seat_id)
                ->whereIn('status', ['confirmed', 'paid', 'pending_payment'])
                ->lockForUpdate()
                ->exists();

            if ($isBooked) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Lỗi: Ghế này vừa được khách hàng khác đặt mua. Vui lòng chọn ghế khác.');
            }

            // 2. Auto-generate Unique Ticket Code
            do {
                $ticketCode = 'MB-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6));
                $exists = Ticket::where('ticket_name', $ticketCode)->exists();
            } while ($exists);

            // 3. Security: Fetch Base Price from DB
            $trip = Trip::findOrFail($request->trip_id);

            Ticket::create([
                'ticket_name' => $ticketCode,
                'user_id' => $user->id,
                'trip_id' => $request->trip_id,
                'seat_id' => $request->seat_id,
                'payment_method_id' => $request->payment_method_id,
                'total' => $trip->base_price,
                'status' => $request->status,
                'purchase_date' => $request->purchase_date,
            ]);

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
