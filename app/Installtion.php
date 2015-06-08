<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Installtion extends Model {

	//
    protected $table = 'installation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['token', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
