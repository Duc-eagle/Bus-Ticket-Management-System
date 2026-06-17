<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();


        // 1. REVENUE ANALYTICS (DOANH THU)


        // Total Revenue This Month

        $totalRevenueThisMonth = Ticket::whereIn('status', ['paid', 'confirmed'])
            ->whereMonth('purchase_date', $now->month)
            ->whereYear('purchase_date', $now->year)
            ->sum('total');

        // Total Revenue This Year (Summary Card)

        $totalRevenueThisYear = Ticket::whereIn('status', ['paid', 'confirmed'])
            ->whereYear('purchase_date', $now->year)
            ->sum('total');

        // Total Tickets Sold (Summary Card)

        $totalTicketsSold = Ticket::where('status', '!=', 'cancelled')->count();

        // Revenue by Day in Current Month (Table -> Chart)
        $revenueByDay = Ticket::select(DB::raw('DATE(purchase_date) as date'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(id) as ticket_count'))
            ->whereIn('status', ['paid', 'confirmed'])
            ->whereMonth('purchase_date', $now->month)
            ->whereYear('purchase_date', $now->year)
            ->groupBy(DB::raw('DATE(purchase_date)'))
            ->orderBy('date', 'asc') // Sort ascending for left-to-right chart
            ->get();

        // Convert to JSON arrays for Chart.js
        $dailyDates = $revenueByDay->pluck('date')->toJson();
        $dailyLabels = $revenueByDay->pluck('date')->map(function($date) {
            return Carbon::parse($date)->format('d/m');
        })->toJson();
        $dailyRevenues = $revenueByDay->pluck('revenue')->toJson();
        $dailyTicketCounts = $revenueByDay->pluck('ticket_count')->toJson();

        // Revenue by Month in Current Year (Table -> Chart)
        $revenueByMonth = Ticket::select(DB::raw('MONTH(purchase_date) as month'), DB::raw('SUM(total) as revenue'), DB::raw('COUNT(id) as ticket_count'))
            ->whereIn('status', ['paid', 'confirmed'])
            ->whereYear('purchase_date', $now->year)
            ->groupBy(DB::raw('MONTH(purchase_date)'))
            ->orderBy('month', 'asc') // Sort ascending for chart
            ->get();

        // Convert to JSON arrays for Chart.js
        $monthlyDates = $revenueByMonth->pluck('month')->toJson();
        $monthlyLabels = $revenueByMonth->pluck('month')->map(function($month) {
            return 'Tháng ' . $month;
        })->toJson();
        $monthlyRevenues = $revenueByMonth->pluck('revenue')->toJson();
        $monthlyTicketCounts = $revenueByMonth->pluck('ticket_count')->toJson();



        // 2. ROUTE POPULARITY ANALYTICS


        // Get ticket counts grouped by Route
        $routePerformance = DB::table('tickets')
            ->join('trips', 'tickets.trip_id', '=', 'trips.id')
            ->join('routes', 'trips.route_id', '=', 'routes.id')
            ->where('tickets.status', '!=', 'cancelled')
            ->select('routes.route_name', DB::raw('COUNT(tickets.id) as ticket_count'))
            ->groupBy('routes.id', 'routes.route_name')
            ->get();

        // Most Booked Routes:
        $mostBookedRoutes = $routePerformance->sortByDesc('ticket_count')->take(5);

        // Least Booked Routes:
        $leastBookedRoutes = $routePerformance->sortBy('ticket_count')->take(5);

        // Calculate max tickets for the progress bar percentage
        $maxTickets = $routePerformance->max('ticket_count') ?: 1;

        return view('dashboard', compact(
            'totalRevenueThisMonth',
            'totalRevenueThisYear',
            'totalTicketsSold',
            'dailyDates',
            'dailyLabels',
            'dailyRevenues',
            'dailyTicketCounts',
            'monthlyDates',
            'monthlyLabels',
            'monthlyRevenues',
            'monthlyTicketCounts',
            'mostBookedRoutes',
            'leastBookedRoutes',
            'maxTickets',
            'now'
        ));
    }
}
