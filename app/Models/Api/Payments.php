<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    protected $table = 'payments';//nếu tên table là books -> không cần
    protected $primaryKey ='id';//Nếu khóa là id -> không cần
    // protected $keyType = 'string';//kiểu khóa chính int -> không cần
    public $incrementing = false;//Khóa chính tự động tăng -> kg cần
    public $timestamps = false;//Nếu có 2 cột: created_at, updated_at-> kg cần

    protected $fillable = [
        'id',
        'id_customer',
        'id_order',
        'amount_paid',
        'amount_unpaid',
        'payment_methods',
    ];
}
