<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'renwar',
            'password' => bcrypt(1),
            'name' => 'Renwar'
        ]);

        User::create([
            'username' => 'rand',
            'password' => bcrypt(1),
            'name' => 'Rand'
        ]);
    }
}
