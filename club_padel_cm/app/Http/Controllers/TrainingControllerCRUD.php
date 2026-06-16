<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingRequest;
use App\Models\Activity;
use App\Models\Court;
use App\Models\Role;
use App\Models\Training;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Devuelve todos los trainings, mostrandolos en 6 en 6 y ordenado por fecha de actualización descendente
        $trainings = Training::orderBy('updated_at', 'desc')->paginate(6);

        // Llamada a la View 'training.index' pasando $trainings para que me muestre todos los trainings
        return view('training.index', ['trainings' => $trainings]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activities = Activity::select('id', 'code', 'name', 'duration', 'peopleMax')->get();
        $courts = Court::select('id', 'courtNum','zone_type_id', 'court_type_id')->get();
        $rolMonitor = Role::where('name', 'monitor')->value('id');
        $users = User::where('role_id', $rolMonitor)->get();
        
        return view('training.create', ['activities' => $activities, 'courts' => $courts, 'users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TrainingRequest $request)
    {
        $training = new Training;

        $training->day = $request->day;
        $training->hour = $request->hour;
        $training->dateIni = Carbon::parse($training['day'])->subMonth();
        $training->dateEnd = Carbon::parse($training['day'])->subWeek();
        $training->activity_id = $request->activity_id;
        $training->user_id = $request->user_id;
        $training->court_id = $request->court_id;
        
        $training->save();

        return redirect()->route('trainingCRUD.index')->with('status','Success: Training creado correctamente'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $trainingCRUD)
    {
        return view('training.show',['training' => $trainingCRUD]);  // trainingCRUD/{trainingCRUD}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $trainingCRUD)
    {
        $activities = Activity::select('id', 'code', 'name', 'duration', 'peopleMax')->get();
        $courts = Court::select('id', 'courtNum','zone_type_id', 'court_type_id')->get();
        $rolMonitor = Role::where('name', 'monitor')->value('id');
        $users = User::where('role_id', $rolMonitor)->get();
        
        return view('training.edit', ['training' => $trainingCRUD, 'activities' => $activities, 'courts' => $courts, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TrainingRequest $request, Training $trainingCRUD)
    {
        $trainingCRUD->update($request->all()); //Actualizamos el registro de la DDBB

        $trainingCRUD->dateIni = Carbon::parse($request->day)->subMonth();
        $trainingCRUD->dateEnd = Carbon::parse($request->day)->subWeek();

        $trainingCRUD->save();

        return redirect()->route('trainingCRUD.index')->with('status','Success: Training actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $trainingCRUD)
    {
        $users_trainings = $trainingCRUD->users_nm()->count(); // Miro si de ese Training que quiero quitar hay algún cliente asociado

        // Si no hay ningún cliente asociado con ese Training, se borra y se devuelve un mensaje de éxito
        if ($users_trainings > 0) {
            // Vuelve a la página llamante con un mensaje de error
            return back()->with('status', 'Error: No se puede eliminar el Training porque tiene clientes asociados'); 
        } else {
            // Elimina el training y vuelve a la página llamante con un mensaje de éxito
            $trainingCRUD->delete(); 
            return back()->with('status', 'Success: Training eliminado correctamente'); 
        }
    }
}
