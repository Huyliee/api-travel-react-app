<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Discount;

class DiscountController extends Controller
{
    public function applyDiscount(Request $r){
        $discountCode = $r->input('discount_code');
        $totalAmount  = $r->input('total');
        $discount = Discount::where('code', $discountCode)->first();

        if ($discount) {
            // Kiểm tra xem mã giảm giá còn hợp lệ hay không
            if ($discount->expired_at && $discount->expired_at < now()) {
                return response()->json(['message' => 'Mã giảm giá đã hết hạn'], 400);
            }
    
            // Áp dụng mã giảm giá vào tổng tiền
            $discountAmount = $totalAmount * $discount->percentage / 100;
            $discountedTotalAmount = $totalAmount - $discountAmount;
    
            // Thực hiện xử lý logic ở đây
    
            return response()->json(['message' => 'Áp dụng mã giảm giá thành công', 'discounted_total_amount' => $discountedTotalAmount], 200);
        }
    
        return response()->json(['message' => 'Mã giảm giá không hợp lệ'], 400);
    }
}
