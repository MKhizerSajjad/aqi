<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {

            $data = [
                'name'         => 'Super Admin',
                'email'        => 'admin@aqi.com',
                'password'     => bcrypt('password'),
            ];
            User::create($data);
        }
    }
}
