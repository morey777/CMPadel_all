<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Training;
use App\Models\User;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cojo los datos del fichero activities.json
        $jsonData = file_get_contents('database\jsons\activities.json');
        $activities = json_decode($jsonData, true);

        // Insertar cada registro en la tabla
        foreach ($activities as $activity) {
            // Mira si el codigo de la actividad existe, si existe no lo crea, de lo contrario lo creará.
            $newActivity = Activity::firstOrCreate([
                'code'     => $activity['codigo']
                ],
                [
                   'name'     => $activity['nombre'],
                   'description'     => $activity['descripcion'],
                   'peopleMax'     => $activity['maxPersonas'],
                   'duration'     => $activity['duracion'],
                   'price'     => $activity['precio'],
                ]
            );

            foreach ($activity['trainings'] as $training) {
                Training::firstOrCreate(
                    ['day' => $training['dia'],
                     'hour'     => $training['hora'],
                     'user_id' => User::where('dni', $training['monitor'])->value('id'),
                     'court_id'     => Court::where('courtNum', $training['numPista'])->value('id')],
                    [
                    'activity_id' => $newActivity->id,
                    'dateIni' => Carbon::parse($training['dia'])->subMonth(),
                    'dateEnd' => Carbon::parse($training['dia'])->subWeek(),
                    ]
                );
            }
        }
    }
}