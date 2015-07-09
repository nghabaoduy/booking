<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FoodOrder extends Model {

	//
    protected $table = 'food_order';
    protected $fillable = ['booking_id', 'name', 'quantity', 'total_price', 'code', 'type'];
    public $timestamps = false;


}
