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
}
