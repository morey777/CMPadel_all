<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourtResource;
use App\Models\Court;
use App\Models\ZoneType;
use Exception;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courts = Court::with(["zoneType", "courtType", "users"])->get();
            return (CourtResource::collection($courts))->additional(['meta' => 'Pistas mostradas correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addUserToCourt(Request $request) // Añado un registro para usuario, aqui no creo una nueva pista
    {
        try {

            // Aqui se calcula que cantidad tendra que pagar el usuario, dependiendo de algunas condiciones
            $court = Court::where('courtNum', $request->pista_num)->firstOrFail(); // si es null da error, y yo lo cojo con un catch

            if ($court->courtType->name === 'Doble' && $court->zoneType->name === 'Exterior') {
                $precio = 12.00;
            } elseif ($court->courtType->name === 'Doble' && $court->zoneType->name === 'Interior') {
                $precio = 14.50;
            } elseif ($court->courtType->name === 'Individual' && $court->zoneType->name === 'Exterior') {
                $precio = 7.00;
            } elseif ($court->courtType->name === 'Individual' && $court->zoneType->name === 'Interior') {
                $precio = 8.70;
            } else {
                throw new \Exception('Tipo de pista o zona no válido');
            }

            $extraMediaHoras = ($request->duracion - 1) / 0.5;

            if ($extraMediaHoras > 0) {
                // Multiplicamos por 2 porque cada 30 min cuesta 2€ (ej: 90 min -> +2€, 120 min -> +4€)
                $precio = $precio + ($extraMediaHoras * 2);
            }

            $court->users()->attach($request->user_id, ["day" => $request->dia, "hour" => $request->hora, "duration" =>  $request->duracion, "price" => $precio] );

            return response()->json([
                'pista'        => $request->pista_num,
                'user'         => $request->user_id,
                'tipoZona'         => $court->zoneType->name,
                'tipoPista'         => $court->courtType->name,
                'duracion'         => $request->duracion*60,
                'precio'         => $precio,
                'status'       => 'El usuario ha podido hacer la reserva correctamente',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
