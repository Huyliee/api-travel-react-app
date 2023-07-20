<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\User;
use App\Models\Api\Tour;
use App\Models\Api\News;
use App\Models\Api\Order;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    public function quantityDataTable(){
        $customer = User::count();
        $tour = Tour::count();
        $news = News::count();
        $order = Order::count();
        $arr = [
            'status' => true,
            'message' => "Danh sách dữ liệu table",
            'customer' => $customer,
            'tour' => $tour,
            'news' => $news,
            'order' => $order,
        ];
        return response()->json($arr,200);
    }
    public function revenueData(){
        $revenueData = User::select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as user_count'))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

    return response()->json($revenueData);
    }

    public function getTourOrdersCount()
    {
        $tours = Tour::with('dateGo.order')->get();

        $result = [];

        foreach ($tours as $tour) {
            $tourData = [
                'tour_id' => $tour->id_tour,
                'tour_name' => $tour->name_tour,
                'order_count_by_date' => [],
            ];

            foreach ($tour->dateGo as $dateGo) {
                $orderCount = $dateGo->order->count();

                $tourData['order_count_by_date'][] = [
                    'date_go' => $dateGo->date,
                    'order_count' => $orderCount,
                ];
            }

            $result[] = $tourData;
        }

        return response()->json($result);
    }

    public function getPriceOfTour(){
        $tours = Tour::with(['dateGo.order'])->get();

$result = [];

foreach ($tours as $tour) {
    $tourData = [
        'tour_id' => $tour->id_tour,
        'tour_name' => $tour->name_tour,
        'order_count_by_date' => [],
        'total_revenue' => 0, // Initialize total revenue to zero for each tour
    ];

    foreach ($tour->dateGo as $dateGo) {
        $orderCount = $dateGo->order->count();
        $totalRevenue = $dateGo->order->sum('total_price'); // Calculate the total revenue for this dateGo

        $tourData['order_count_by_date'][] = [
            'date_go' => $dateGo->date,
            'order_count' => $orderCount,
            'total_revenue' => $totalRevenue,
        ];

        $tourData['total_revenue'] += $totalRevenue; // Accumulate total revenue for each tour
    }

    $result[] = $tourData;
}

return response()->json($result);

    }
}
