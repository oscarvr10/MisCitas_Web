<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Roberto Torres',
            'email' => 'beto@test.com',
            'password' => password_hash('qwerty', PASSWORD_BCRYPT),
            'id_card' => Str::random(16),
            'address' => '',
            'phone' => '',
            'role' => 'admin'
        ]);
        factory(User::class, 50)->create();
    }
}