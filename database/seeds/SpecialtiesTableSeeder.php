<?php

use Illuminate\Database\Seeder;
use App\Specialty;

class SpecialtiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Specialty::create(['name' => 'Cardiología', 'description' => '']);
        Specialty::create(['name' => 'Pediatría', 'description' => '']);
        Specialty::create(['name' => 'Ginecología', 'description' => '']);
        Specialty::create(['name' => 'Psiquiatría', 'description' => '']);
        Specialty::create(['name' => 'Endocrinología', 'description' => '']);
        Specialty::create(['name' => 'Nutriología', 'description' => '']);           
    }
}
