<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $user1 = User::create([

            "ci" => "30847627",
            "name" => "Juan",
            "last_name" => "Donquis",
            "email" => "juandonquis07@gmail.com",
            "password" => Hash::make('admin'),
            "phone_number" => "04125800610",
            "search" => "Juan Donquis juandonquis07@gmail.com 30847627 04125800610"
        ]);

        $user2 = User::create([

            "ci" => "27253194",
            "name" => "Juan",
            "last_name" => "Villasmil",
            "email" => "juanvillans16@gmail.com",
            "password" => Hash::make('admin'),
            "phone_number" => "04124393123",
            "search" => "Juan Villasmil juanvillans16@gmail.com 27253194 04124393123"
        ]);

        $user3 = User::create([

            "ci" => "14027371",
            "name" => "Jose",
            "last_name" => "Rodriguez",
            "email" => "joserodriguez@gmail.com",
            "password" => Hash::make('doctor'),
            "phone_number" => "04143672200",
            "search" => "Juan Rodriguez joserodriguez@gmail.com 27253194 04143672200"
        ]);

        $user1->assignRole('admin');
        $user2->assignRole('admin');
        $user3->assignRole('doctor');

        $user3->specialties()->sync([ 1, 2 ]);




    }
}
