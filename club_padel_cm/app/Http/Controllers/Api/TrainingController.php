<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrainingResource;
use App\Models\Training;
use Exception;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $trainings = Training::with(["activity", "users_nm"])->get();
            return (TrainingResource::collection($trainings))->additional(['meta' => 'Trainings mostrados correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'S\'ha produït un error al tractar les dades',
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //    
    // }

    public function addUserToTraining(Request $request) // Añado un registro para usuario, para el tema de training
    {
        try {
            $training = Training::findOrFail($request->id_training);

            // Miro si el usuario que me pasan no este repetido en el training, si lo esta lo elimino si no, lo añado
            if ($training->users_nm()->where('user_id', $request->user_id)->exists()) {
                $training->users_nm()->detach($request->user_id);
                $mensage = "Quitando usuario del Training";
            } else {

                // Antes de añadir si no estaba, miro si el training no esta lleno
                if ($training->users_nm()->count() >= $training->activity->peopleMax) {
                    return response()->json([
                        "success" => false,
                        "message" => "Esta lleno el training"
                    ]);
                }

                $training->users_nm()->attach($request->user_id);
                $mensage = "Metiendo usuario al Training";
            }
            $training->load('activity')->load('users_nm');
            return (new TrainingResource($training))->additional(['meta' => $mensage]);
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
    public function show(Training $training) // coge el id por defecto, getRouteKeyName -> no lo tengo pero si no lo pongo en Model de Training coge el id
    {
        try {
            $training->load('activity')->load('users_nm'); 
            return (new TrainingResource($training))->additional(['meta' => 'Training mostrado correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                'error_details' => $e->getMessage(),
            ], 200);
        }
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
