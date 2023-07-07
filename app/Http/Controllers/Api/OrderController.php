<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Order;
use App\Models\Api\Tour;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::all();
        $arr = [
            'status' => true,
            'message' => "Danh sách đơn đặt tour",
            'data' => $order
        ];
        return response()->json($arr,200);
    }

    public function findOrder($id)
    {
        $order = Order::where('id_customer',$id)->get();
        $arr = [
            'status' => true,
            'message' => "Danh sách đơn đặt tour",
            'data' => $order
        ];
        return response()->json($arr,200);
    }
public function orderCustomer($id)
{
    $order = Order::where('id_customer', $id)->get();


    // Tạo một mảng mới chứa thông tin của mỗi đơn đặt tour và trường dataDate
    $orderData = [];
    foreach ($order as $orderItem) {
        $id_order = $orderItem['id_order_tour'];
        $order_datego = Order::find($id_order);
        $dateGo = $order_datego->date_go;
        $orderData[] = [
            'order' => $orderItem,
            'dataDate' => $dateGo
        ];
    }

    $arr = [
        'status' => true,
        'message' => "Danh sách đơn đặt tour theo khách hàng",
        'data' => $orderData
    ];

    return response()->json($arr, 200);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id , $id_tour)
    {
        $order = Order::find($id);
        $tour = Tour::find($id_tour);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            if ($order->detailOrder()->count() > 0) {
                return response()->json(['message' => 'Không thể xóa đơn đặt vì tồn tại chi tiết hóa đơn'], 400);
            } else {
                $order->delete();
                return response()->json(['message' => 'Đơn đặt tour đã được xóa thành công.'], 200);
            }
        } else {
            return response()->json(['message' => 'Không tìm thấy đơn đặt hàng'], 404);
        }
    }
    public function accept($id){
        $order = Order::find($id);
        $order->status = "Yes";
        $order->save();
        return response()->json(['order' => $order], 201);
    }
}
