<?php

use Illuminate\Database\Seeder;
use App\User;
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
        $specialties = [
            'Cardiología',
            'Pediatría',
            'Ginecología',
            'Psiquiatría',
            'Nutriología',
        ];
        $specialty = null;
        
        foreach ($specialties as $specialtyName) {
            $specialty =  Specialty::create([
                'name' => $specialtyName
            ]);

            $specialty->users()->saveMany(
                factory(User::class, 3)->states('doctor')->make()
            );
        }

        User::find(2)->specialties()->save($specialty);
    }
}
