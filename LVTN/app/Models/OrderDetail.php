<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class OrderDetail extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_size_id',
        'unit_price',
        'quantity',
        'import_price'
    ];

    public function productSize()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id', 'id');
    }

    // Mối quan hệ với bảng Size thông qua ProductSize
    public function size()
    {
        return $this->productSize->size();
    }

    public function color()
    {
        return $this->productSize->productColor->color();
    }
}