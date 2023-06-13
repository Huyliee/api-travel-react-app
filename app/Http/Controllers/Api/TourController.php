<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Tour;
use App\Models\Api\Order;
use App\Models\Api\DetailOrder;
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
        $tour = Tour::with('images','dateGo')->find($id);
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

        //thêm ảnh lên clound ImageUrl
        $imgur = Http::withHeaders([
            'Authorization' => "Client-ID $client_id",
            'Authorization' => "Bearer $access_token",
        ])->attach('image', file_get_contents($request->file('img_tour')->getRealPath()), 'image.jpg')
          ->post('https://api.imgur.com/3/image');
        $tour->img_tour = $imgur['data']['link'];

        //thêm tour
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
        $tour->best_seller = $request->input('best_seller');
        $tour->hot_tour = $request->input('hot_tour');

        $client_id = env('IMGUR_CLIENT_ID');
        $client_secret = env('IMGUR_CLIENT_SECRET');
        $access_token = env('IMGUR_ACCESS_TOKEN');
        $oldImg = $tour->img_tour;
        $newImg = $request->file('img_tour');
        
        if($newImg){
            $imgur = Http::withHeaders([
                'Authorization' => "Client-ID $client_id",
                'Authorization' => "Bearer $access_token",
            ])->attach('image', file_get_contents($newImg->getRealPath()), 'image.jpg')
              ->post('https://api.imgur.com/3/image');
            $tour->img_tour = $imgur['data']['link'];
            if($oldImg){
                 //phan tích url
                $parsedUrl = parse_url($oldImg);
                //lấy path của ảnh
                $path = $parsedUrl['path'];
                //show thông tin của path như id ảnh , tên ảnh , ...
                $pathParts = pathinfo($path);
                //láy id ảnh
                $imageId = $pathParts['filename']; 

                //xóa ảnh trên clound ImageUrl
                Http::withHeaders([
                    'Authorization' => "Client-ID $client_id",
                    'Authorization' => "Bearer $access_token",
                ])->delete("https://api.imgur.com/3/image/$imageId");
            }
        }
        $tour->save();
        return response()->json($tour, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client_id = env('IMGUR_CLIENT_ID');
        $client_secret = env('IMGUR_CLIENT_SECRET');
        $access_token = env('IMGUR_ACCESS_TOKEN');

        $tour = Tour::findOrFail($id);
        $imageUrl = $tour->img_tour;

        //phan tích url
        $parsedUrl = parse_url($imageUrl);
        //lấy path của ảnh
        $path = $parsedUrl['path'];
        //show thông tin của path như id ảnh , tên ảnh , ...
        $pathParts = pathinfo($path);
        //láy id ảnh
        $imageId = $pathParts['filename']; 

        //xóa ảnh trên clound ImageUrl
        Http::withHeaders([
            'Authorization' => "Client-ID $client_id",
            'Authorization' => "Bearer $access_token",
        ])->delete("https://api.imgur.com/3/image/$imageId");

        //xóa tour
        $tour->delete();
        return response()->json(['message'=>'Xóa thành công'], 204);
    }

    public function checkout(Request $r , $id){
        $tour = Tour::find($id);
        $data = $r->all();
        $quantity = $r->qty;

        // Lưu thông tin liên hệ
        $data['status']="No";
        $data['order_time'] = date("Y-m-d H:i:s");
        $data['id_order_tour'] = time();
        $order = Order::create($data);

        $adultInfo = $r->input('detail.adultInfo');
        $childInfo = $r->input('detail.childInfo');
        
        // Lưu thông tin khách hàng người lớn
        foreach ($adultInfo as $adult) {
            $data = [
                'id_order' => $order->id_order_tour,
                'id_tour' => $tour->id_tour,
                'name_customer' => $adult['name_customer'],
                'sex' => $adult['gender'],
                'birth' => $adult['date'],
                'CMND' => time(),
                'age' => $adult['age']
            ];
            DetailOrder::create($data);
        }
        
        // Lưu thông tin khách hàng trẻ em
        foreach ($childInfo as $child) {
            $data = [
                'id_order' => $order->id_order_tour,
                'id_tour' => $tour->id_tour,
                'name_customer' => $child['name_customer'],
                'sex' => $child['gender'],
                'birth' => $child['date'],
                'CMND' => time(),
                'age' => $child['age']
            ];
            DetailOrder::create($data);
        }
        $od = DetailOrder::where('id_order',$order->id_order_tour)->get();
        return response()->json(['detail'=>$od,'order'=>$order], 201);
    }

    public function detailOrder ($id){
        $order = Order::with('detail_order')->find($id);
        $arr = [
            'status' => true,
            'data' => $order
        ];
        return response()->json($arr,200);
    }
}
