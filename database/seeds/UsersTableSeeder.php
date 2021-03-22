<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;

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
            'address' => 'Avenita Test',
            'phone' => '5520304522',
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'Dra. Olivia Conner',
            'email' => 'oconner.dylan@example.net',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'id_card' => Str::random(16),
            'address' => 'Avenita Test',
            'phone' => '5520304522',
            'role' => 'doctor'
        ]);
        User::create([
            'name' => 'Joey Luna',
            'email' => 'joey07@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'id_card' => Str::random(16),
            'address' => 'Avenita Test',
            'phone' => '5520304522',
            'role' => 'patient'
        ]);
        
        factory(User::class, 50)->states('patient')->create();
    }
}