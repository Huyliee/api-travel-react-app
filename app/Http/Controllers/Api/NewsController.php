<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\News;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::all();
        $arr = [
            'status' => true,
            'message' => "Danh sách tin tức",
            'data' => $news
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
        $news = new News();
        $client_id = env('IMGUR_CLIENT_ID');
        $client_secret = env('IMGUR_CLIENT_SECRET');
        $access_token = env('IMGUR_ACCESS_TOKEN');
        $news->id_news = $request->input('id_news');
        $news->title_news = $request->input('title_news');
        $news->date = $request->input('date');
        $img_news = $request->file('img_news');
        $news->content_news = $request->input('content_news');
        

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
                    'contents' => fopen($img_news->getPathname(), 'r'),
                ],
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        $news->img_news = $responseData['data']['link'];

        //thêm news
        $news->save();
        return response()->json($news, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::find($id);
        $arr = [
            'status' => true,
            'data' => $news
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
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['message'=>'Xóa thành công'], 204);
    }
}
