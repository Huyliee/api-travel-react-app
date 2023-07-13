<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Api\DateGo;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order_tour';//nếu tên table là books -> không cần
    protected $primaryKey ='id_order_tour';//Nếu khóa là id -> không cần
    // protected $keyType = 'string';//kiểu khóa chính int -> không cần
    public $incrementing = false;//Khóa chính tự động tăng -> kg cần
    public $timestamps = false;//Nếu có 2 cột: created_at, updated_at-> kg cần

    protected $fillable = [
        'id_order_tour',
        'order_time',
        'status',
        'email',
        'name',
        'phone',
        'address',
        'id_customer',
        'id_date',
    ];

    public function detail_order()
    {
        return $this->hasMany(DetailOrder::class,"id_order","id_order_tour");
    }
    public function date_go()
    {
        return $this->belongsTo(DateGo::class,"id_date","id");
    }


}
