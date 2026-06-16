<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Role;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrainingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainings = Training::all();

        $roleId = Role::where('name', 'cliente')->value('id'); // value: Devuelve un solo valor

        $usersIds = User::where('role_id', $roleId)->pluck('id'); // pluck: Devuelve una colección con uno o varios valores

        foreach ($trainings as $training) {

            $personasMax = Activity::where('id', $training->activity_id)->value('PeopleMax');
            
            // La idea es coger 5 clientes en principio, pero si no es posible por el límite impuesto por la actividad, se usa el máximo permitido -1
            if (5 <= $personasMax) {
                $randomUsersIds = $usersIds->random(5); // Cojo 5 clientes
            } else {
                $randomUsersIds = $usersIds->random($personasMax-1);
            }

            for ($r=0; $r<$randomUsersIds->count(); $r++) {
                // echo "Usuario: $randomUsersIds[$r]\n"; // $randomUsersIds[$r] --> el id del usuario
                $training->users_nm()->attach($randomUsersIds[$r]); // Asocio por el training al que estoy a cada usuario que he escogido aleatoriamente
            }
        }
    }
}
