<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\CourtType;
use App\Models\User;
use App\Models\ZoneType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cojo los datos del fichero courts.json
        $jsonData = file_get_contents('database\jsons\courts.json');
        $courts = json_decode($jsonData, true);

        foreach ($courts as $court) {

            // Busca el tipo de zona por nombre, si no existe lo crea de lo contrario no lo crea.
            $zoneType = ZoneType::firstOrCreate([
                'name'  => $court['tipoZona']
            ]);

            // Idem, para tipo pista
            $courtType = CourtType::firstOrCreate([
                'name'  => $court['tipoPista']
            ]);

            $newCourt = Court::firstOrCreate([
                'courtNum'     => $court['numPista']
                ],
                [
                   'zone_type_id'     => $zoneType->id,
                   'court_type_id'     => $courtType->id
                ]
            );
            // echo "pista: $newCourt\n";
            $usersRan = User::where('role_id', 3)->pluck('id')->random(count($court['reservas'])); // Obtiene tantos IDs de usuarios aleatorios (solamente a los clientes) como reservas tenga la pista.

            // print_r($usersRan->toArray());
            // echo " nose ";

            // echo count($court['reservas']);

            foreach ($court['reservas'] as $index => $reserva) {

                if ($courtType->name === 'Doble' && $zoneType->name === 'Exterior') {
                    $precio = 12.00;
                } elseif ($courtType->name === 'Doble' && $zoneType->name === 'Interior') {
                    $precio = 14.50;
                } elseif ($courtType->name === 'Individual' && $zoneType->name === 'Exterior') {
                    $precio = 7.00;
                } elseif ($courtType->name === 'Individual' && $zoneType->name === 'Interior') {
                    $precio = 8.70;
                }  else {
                    throw new \Exception('Tipo de pista o zona no válido');
                }

                $extraMediaHoras = ($reserva["duracion"] - 1) / 0.5;

                if ($extraMediaHoras > 0) {
                    // Multiplicamos por 2 porque cada 30 min cuesta 2€ (ej: 90 min -> +2€, 120 min -> +4€)
                    $precio = $precio + ($extraMediaHoras * 2);
                }

                $newCourt->users()->attach($usersRan[$index], ["day" => $reserva["dia"], "hour" => $reserva["hora"], "duration" => $reserva["duracion"], "price" => $precio] );

            }
        }
    }
}
