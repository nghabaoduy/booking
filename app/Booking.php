<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model {

	//
    protected $table = 'booking';
    protected $fillable = ['is_confirmed', 'time_slot', 'is_rescheduled', 'user_id', 'time_slot', 'booking_ref', 'is_paid', 'total_order', 'type', 'additional_info'];


    public function foodOrders() {
        return $this->hasMany('App\FoodOrder', 'booking_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

}
