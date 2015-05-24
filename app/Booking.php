<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model {

	//
    protected $table = 'booking';
    protected $fillable = ['is_confirmed', 'time_slot', 'is_rescheduled', 'user_id', 'time_slot', 'booking_ref', 'is_paid', 'total_order'];


    public function foodOrders() {
        return $this->hasMany('App\FoodOrder', 'booking_id');
    }

}
