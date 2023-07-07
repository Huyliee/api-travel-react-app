<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        $arr = [
            'status' => true,
            'message' => "Danh sách khách hàng",
            'data' => $user
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
        $user = new User;
        $user->customer_name = $request->input('customer_name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->img = $request->input('img');
        $user->gender = $request->input('gender');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->permission = $request->input('permission');
        $user->save();
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $arr = [
            'status' => true,
            'data' => $user
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
    public function getCustomer($email){
        $user = User::where('email',$email)->get();
        return response()->json($user, 201);
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
        $user = User::findOrFail($id);
        $user->customer_name = $request->input('customer_name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->img = $request->input('img');
        $user->gender = $request->input('gender');
        $user->date_of_birth = $request->input('date_of_birth');
        $user->permission = $request->input('permission');
        $user->save();
        return response()->json($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message'=>'Xóa thành công'], 204);
    }
}
