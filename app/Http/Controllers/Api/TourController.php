<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Tour;
use App\Models\Api\Order;
use App\Models\Api\DateGo;
use App\Models\Api\DetailOrder;
use App\Models\Api\TourLocation;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

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
        $price = $r->input('adult_price');
        $tourType = $r->input('tourType');
        $tourLocationId = $r->input('id_location');
    
        $query = Tour::query();
    
        if (!empty($name)) {
            $query->where('name_tour', 'like', '%' . $name . '%');
        }
    
        if (!empty($price)) {
            $query->where('adult_price', '>=', $price);
        }
    

        if (!empty($tourLocationId)) {
            $query->whereHas('location', function ($q) use ($tourLocationId) {
                $q->where('id_location', $tourLocationId);
            });
        }

                
        $tours = $query->paginate(4);
    
        return response()->json(['tours' => $tours]);
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
        $tour->content_tour = $request->input('content_tour');
        $tour->place_go = $request->input('place_go');
        $tour->child_price = $request->input('child_price');
        $tour->adult_price = $request->input('adult_price');
        $img_tour = $request->file('img_tour');
        $tour->best_seller = $request->input('best_seller');
        $tour->hot_tour = $request->input('hot_tour');

        //thêm ảnh lên clound ImageUrl
        $client = new Client();

        $response = $client->request('POST', 'https://api.imgur.com/3/image', [
            'headers' => [
                'Authorization' => 'Client-ID 0e40bfbb10cc564',
                'Authorization' => "Bearer b1b610785d40e309d8111cbae409edbf55184545"
            ],
            'multipart' => [
                [
                    'name' => 'image',
                    'contents' => fopen($img_tour->getPathname(), 'r'),
                ],
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        $tour->img_tour = $responseData['data']['link'];
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
    
        if ($request->hasFile('img_tour')) {
            $img_tour = $request->file('img_tour');
            
            // Lấy đường dẫn tạm thời của tệp tin
            $tempFilePath = $img_tour->getPathname();
      
            $client = new Client();
            $response = $client->request('POST', 'https://api.imgur.com/3/image', [
                'headers' => [
                    'Authorization' => 'Bearer b1b610785d40e309d8111cbae409edbf55184545',
                ],
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($tempFilePath, 'r'),
                    ],
                ],
            ]);
      
            $responseData = json_decode($response->getBody(), true);
            $tour->img_tour = $responseData['data']['link'];
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

        $tour = Tour::with('dateGo.order.detail_order')->find($id);

        $hasOrders = $tour->dateGo->flatMap(function ($dateGo) {
            return $dateGo->order->pluck('detail_order');
        })->pluck('id_tour')->contains($id);
        $imageUrl = $tour->img_tour;

        // Phân tích cú pháp link ảnh để lấy phần cuối cùng
        $imagePath = parse_url($imageUrl, PHP_URL_PATH);
        
        // Lấy tên tệp ảnh từ đường dẫn
        $imageFileName = basename($imagePath);
        
        // Trích xuất $imageId bằng cách loại bỏ phần đuôi tệp
        $imageId = pathinfo($imageFileName, PATHINFO_FILENAME);

        $client = new Client();
        $response = $client->request('GET', "https://api.imgur.com/3/image/$imageId", [
            'headers' => [
                'Authorization' => 'Bearer b1b610785d40e309d8111cbae409edbf55184545',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        $imageDeleteHash = $responseData['data']['deletehash'];
        

        //xóa ảnh trên clound ImageUrl
        $client = new Client();
        $response = $client->request('DELETE', "https://api.imgur.com/3/image/$imageDeleteHash", [
            'headers' => [
                'Authorization' => 'Bearer b1b610785d40e309d8111cbae409edbf55184545',
            ],
        ]);

        //xóa tour
        if($hasOrders)
        {
            return response()->json(['message'=>'Xóa không thành công vì tour còn đơn đặt tour'], 204);
        }else{
            $tour->delete();
            return response()->json(['message'=>'Xóa thành công'], 204);
        }
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
        Mail::to($data['email'])->send(new OrderConfirmationMail($order));
        $adultInfo = $r->input('detail.adultInfo');
        $childInfo = $r->input('detail.childInfo');

        $totalQuantity = count($adultInfo) + count($childInfo);
        $idDateGo = $order->id_date;
        $dateGo = DateGo::find($idDateGo);
        $seats = $dateGo->seats;
        $updateDate =  $seats - $totalQuantity;
        $dateGo->seats = $updateDate;
        $dateGo->save();
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
