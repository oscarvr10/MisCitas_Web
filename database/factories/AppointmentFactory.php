<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Appointment::class, function (Faker $faker) {
    $doctorIds = User::doctors()->pluck('id');
    $patientIds = User::patients()->pluck('id');
    $date = $faker->dateTimeBetween('-1 years', 'now');
    $scheduledDate = $date->format('Y-m-d');
    $scheduledTime = $date->format('H:i:s');
    $types = ['Exámen', 'Consulta', 'Operación'];
    $statuses =  ['Reservada', 'Confirmada', 'Atendida', 'Cancelada'];

    return [
        'description' => $faker->sentence(10),
        'specialty_id' => $faker->numberBetween(1, 5),
        'doctor_id' => $faker->randomElement($doctorIds),
        'patient_id' => $faker->randomElement($patientIds),
        'scheduled_date' => $scheduledDate,
        'scheduled_time' => $scheduledTime,
        'type' => $faker->randomElement($types),
        'status' => $faker->randomElement($statuses),
    ];
});
