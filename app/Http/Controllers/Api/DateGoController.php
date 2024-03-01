<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\DateGo;

class DateGoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dateGo = DateGo::all();
        $arr = [
            'status' => true,
            'message' => "Danh sách tin tức",
            'data' => $dateGo
        ];
        return response()->json($arr,200);
    }

    public function order($id){
        $dateGo = DateGo::with('order.detail_order')->find($id);
        $detailOrders = $dateGo->order->flatMap(function ($order) {
            return $order->detail_order;
        });

        $arr = [
            'status' => true,
            'data' => $detailOrders
        ];
        return response()->json($arr,200);
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
        $dateGo = new DateGo;
        $dateGo->id_tour = $request->input('id_tour');
        $dateGo->date = $request->input('date');
        $dateGo->id_guide = $request->input('id_guide');
        $dateGo->seats = $request->input('seats');
        $dateGo->save();
        return response()->json($dateGo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datego = DateGo::find($id);
        $arr = [
            'status' => true,
            'data' => $datego,
        ];
        return response()->json($arr,200);
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
        //
    }
}
