<?php

use Illuminate\Database\Seeder;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;
use App\User;
use Faker\Factory;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // TestDummy::times(20)->create('App\Post');

        $faker = Faker\Factory::create();
        $data = [
            'phone' => '84261124',
            'email' => 'nguyen.habaoduy@gmail.com',
            'password' => bcrypt('000000'),
            'first_name' => 'Duy',
            'last_name'=> 'Nguyen',
            'type' => 'customer',
            'api_token' => $this->generateRandomString(45)
        ];
        User::create($data);

        for($i = 0; $i < 10; $i++) {
            $data = [
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'password' => bcrypt('000000'),
                'first_name' => $faker->firstName,
                'last_name'=> $faker->lastName,
                'type' => 'customer',
                'api_token' => $this->generateRandomString(45)
            ];
            User::create($data);
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

