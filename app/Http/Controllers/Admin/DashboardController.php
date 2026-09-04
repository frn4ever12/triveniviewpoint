<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TableStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
      
        $orders=Order::get();

        $orderitems=OrderItem::get();

        // Orders today
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();

        // Orders yesterday
        $ordersYesterday = Order::whereDate('created_at', Carbon::yesterday())->count();

        // Calculate percentage change
        if ($ordersYesterday > 0) {
            $ordersChange = (($ordersToday - $ordersYesterday) / $ordersYesterday) * 100;
        } else {
            $ordersChange = $ordersToday > 0 ? 100 : 0; // Avoid division by zero
        }

        // Pass rounded value
        $ordersChange = round($ordersChange, 1);


        $latestOrders = Order::with(['table'])
            ->withSum('items', 'total')
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->take(10) 
            ->get();
        

        // calculate status counts for badges
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        //tables
        $tables=Table::get();

        $totalTables = Table::count();

        $occupiedTables = Table::where('status', TableStatusEnum::OCCUPIED)->count();

        $occupancyPercent = $totalTables > 0 ? round(($occupiedTables / $totalTables) * 100) : 0;

        $latestTables = Table::
            orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        //revenue
        $today = Carbon::today();
        
        $yesterday = Carbon::yesterday();
    
        // Total revenue today
        $todaysRevenue = Invoice::whereDate('created_at', $today)
            ->sum('total_amount');
    
        // Total revenue yesterday
        $yesterdaysRevenue = Invoice::whereDate('created_at', $yesterday)
            ->sum('total_amount');
    
        // Calculate percentage change
        $revenueChange = $yesterdaysRevenue > 0 
            ? (($todaysRevenue - $yesterdaysRevenue) / $yesterdaysRevenue) * 100
            : 100;

        //trend graph
        $weekLabels = [];

        $weekRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $weekLabels[] = $day->format('D'); // Mon, Tue, etc.

            $weekRevenue[] = Invoice::whereDate('created_at', $day)
                ->sum('total_amount');
        }        

        return view('dashboard',
        compact('tables','orders','orderitems','latestOrders',
        'statusCounts','latestTables','ordersToday','ordersYesterday','ordersChange','totalTables',
        'occupiedTables','occupancyPercent','todaysRevenue','revenueChange','weekLabels', 
        'weekRevenue',));
    }

    public function getTodaysDishRevenue(Request $request)
    {
        $today = Carbon::today();
    
        $dishRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->whereDate('orders.created_at', $today) // ✅ use orders.created_at
            ->select([
                'dishes.id as dish_id',
                'dishes.name as dish_name',
                'order_items.unit_price',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.total) as total_amount')
            ])
            ->groupBy('dishes.id', 'dishes.name', 'order_items.unit_price')
            ->orderByDesc('total_amount')
            ->get();
    
        $totalRevenue = $dishRevenue->sum('total_amount');
    
        return response()->json([
            'success' => true,
            'dishes' => $dishRevenue,
            'total_revenue' => $totalRevenue,
            'date' => $today->format('F j, Y')
        ]);
    }
    

}
