<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Tour;
use Illuminate\Support\Facades\Http;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tour = Tour::all();
        $arr = [
            'status' => true,
            'message' => "Danh sách tour",
            'data' => $tour
        ];
        return response()->json($arr,200);
    }
    public function detail($id)
    {
        $tour = Tour::with('images')->find($id);
        $arr = [
            'status' => true,
            'data' => $tour
        ];
        return response()->json($arr,200);
    }

    public function pagnination()
    {
        $tour = Tour::paginate(4);
        return response()->json(['data' => $tour]);
    }

    public function search(Request $r)
    {
        $name = $r->input('name');

        $tours = Tour::where('name_tour','like','%'.$name.'%')->get();

        return response()->json(['tours'=>$tours]);
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
        $tour = new Tour;
        $client_id = env('IMGUR_CLIENT_ID');
        $client_secret = env('IMGUR_CLIENT_SECRET');
        $access_token = env('IMGUR_ACCESS_TOKEN');
        $tour->id_tour = $request->input('id_tour');
        $tour->name_tour = $request->input('name_tour');
        $tour->date_back = $request->input('date_back');
        $tour->content_tour = $request->input('content_tour');
        $tour->place_go = $request->input('place_go');
        $tour->child_price = $request->input('child_price');
        $tour->adult_price = $request->input('adult_price');
        // $tour->img_tour = $request->input('img_tour');
        $tour->best_seller = $request->input('best_seller');
        $tour->hot_tour = $request->input('hot_tour');
        // $imgur = Http::withHeaders([
        //     'Authorization' => "Client-ID $client_id",
        //     'Authorization' => "Bearer $access_token",
        // ])->attach('image', file_get_contents($request->file('img_tour')->getRealPath()), 'image.jpg')
        //   ->post('https://api.imgur.com/3/image');
        // $tour->img_tour = $imgur['data']['link'];
        $tour->save();
        return response()->json($tour, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tour = Tour::findOrFail($id);
        return response()->json($tour);
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
        $tour = Tour::findOrFail($id);
        $tour->name_tour = $request->input('name_tour');
        $tour->date_back = $request->input('date_back');
        $tour->content_tour = $request->input('content_tour');
        $tour->place_go = $request->input('place_go');
        $tour->child_price = $request->input('child_price');
        $tour->adult_price = $request->input('adult_price');
        $tour->img_tour = $request->input('img_tour');
        $tour->best_seller = $request->input('best_seller');
        $tour->hot_tour = $request->input('hot_tour');
        $tour->save();
        return response()->json($tour, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tour = Tour::findOrFail($id);
        $tour->delete();
        return response()->json(['message'=>'Xóa thành công'], 204);
    }
}
