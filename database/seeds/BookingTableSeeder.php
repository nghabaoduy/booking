<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use Faker\Factory;
use App\Booking;
use App\FoodOrder;

class BookingTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');
        $faker = Faker\Factory::create();


        for ($i  = 0; $i < 10; $i++) {
            $data = [
                'is_confirmed' => 0,
                'time_slot' => '2015-05-24 20:00:00',
                'is_rescheduled' => 0,
                'user_id' => 12,
                'booking_ref' => $this->generateRandomString(10),
                'is_paid' => 0,
                'total_order' => 120.00,
                'type' => 'dinning'
            ];
            $booking = Booking::create($data);
            for ($x  = 0; $x < 10; $x++) {
                $foodData = [
                    'booking_id' => $booking->id,
                    'name' => $faker->name,
                    'quantity' => $faker->numberBetween(3, 10),
                    'total_price' => $faker->numberBetween(10, 20)
                ];
                FoodOrder::create($foodData);
            }
        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
