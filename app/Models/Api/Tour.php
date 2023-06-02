<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\Image;

class Tour extends Model
{
    use HasFactory;
    protected $table = 'tour';//nếu tên table là books -> không cần
    protected $primaryKey ='id_tour';//Nếu khóa là id -> không cần
    protected $keyType = 'string';//kiểu khóa chính int -> không cần
    public $incrementing = false;//Khóa chính tự động tăng -> kg cần
    public $timestamps = false;//Nếu có 2 cột: created_at, updated_at-> kg cần

    protected $fillable = [
        'id_tour',
        'name_tour',
        'date_back',
        'content_tour',
        'child_price',
        'adult_price',
        'img_tour',
        'best_seller',
        'hot_tour',
    ];

    public function images()
    {
        return $this->hasMany(Image::class,"id_tour","id_tour");
    }

}
