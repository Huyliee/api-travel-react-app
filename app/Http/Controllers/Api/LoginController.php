<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $r)
    { 
        $user = User::where('email',$r->input('email'))->first();
        $permission = $user->permission;
        if($user)
        {
            if(hash::check($r->input('password'),$user->password)){
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'permission'=>$permission,
                    'message'=> 'Chào '.$user->customer_name,
                    'access_token' =>$token,
                    'type_token' =>'Bearer',
                ]);   
            }else{
                return response()->json(['message'=>'Sai mật khẩu'],401);
            }
        }else{
            return response()->json(['message'=>'Sai thông tin'],401);
        }
    }
    public function register(Request $r)
    {
        $data = $r->all();
        $data['password'] = Hash::make($r->password);
        $email = User::where('email',$r->input('email'))->first();
        // $email = User::where('email',$data['email']);
        if($email){
            $toast = "true";
            return response()->json(['error'=>'Email này đã được sử dụng để đăng ký cho tài khoản khác','toast' => $toast]);
        }else{
            $user = User::Create($data);
            return response()->json(['user' => $user], 201);
        }
    }
    public function logout(Request $r)
    {
        $r->user()->tokens()->delete();
        return response()->json(['message' => 'Đăng xuất thành công!']);
    }
}
