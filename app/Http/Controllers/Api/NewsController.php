<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\News;
use Illuminate\Support\Facades\Http;

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
        $news->content_news = $request->input('content_news');
        

        //thêm ảnh lên clound ImageUrl
        $imgur = Http::withHeaders([
            'Authorization' => "Client-ID $client_id",
            'Authorization' => "Bearer $access_token",
        ])->attach('image', file_get_contents($request->file('img_news')->getRealPath()), 'image.jpg')
          ->post('https://api.imgur.com/3/image');
        $news->img_news = $imgur['data']['link'];

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
        //
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
