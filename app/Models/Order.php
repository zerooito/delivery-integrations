<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    protected $fillable = [
        'order_id',
        'full_code',
        'code',
        'event_id',
        'customer_id'
    ];

    public static function getAllOrdersPlaced($customerId)
    {
        return self::where('customer_id', $customerId)->where('status', 'PLACED')->get();
    }

    public static function getAllOrdersConfirmed($customerId)
    {
        return self::where('customer_id', $customerId)->where('status', 'CONFIRMED')->get();
    }
}
