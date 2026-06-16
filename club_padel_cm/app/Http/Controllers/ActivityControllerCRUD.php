<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Training;
use Illuminate\Http\Request;

class ActivityControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Devuelve todas las actividades, mostrandolas en 6 en 6 y ordenado por fecha de actualización descendente
        $activities = Activity::orderBy('updated_at', 'desc')->paginate(6);

        // Llamada a la View 'activity.index' pasando $activities para que me muestre todas las activities
        return view('activity.index', ['activities' => $activities]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityRequest $request)
    {
        $activity = new Activity;

        $activity->code = 'ACT-' . $activity->id;
        $activity->name = $request->name;
        $activity->description = $request->description;
        $activity->price = $request->price;
        $activity->peopleMax = $request->peopleMax;
        $activity->duration = $request->duration;

        $activity->save();

        $activity->update([
            'code' => 'ACT-' . $activity->id
        ]);
        
        $activity->save();

        return redirect()->route('activityCRUD.index')->with('status','Success: Actividad creada correctamente'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activityCRUD)
    {
        return view('activity.show', ['activity' => $activityCRUD]);  // activityCRUD/{activityCRUD}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activityCRUD)
    {
        return view('activity.edit', ['activity' => $activityCRUD]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityRequest $request, Activity $activityCRUD)
    {
        $activityCRUD->update($request->all()); //Actualizamos el registro de la DDBB
        return redirect()->route('activityCRUD.index')->with('status','Success: Activity actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activityCRUD)
    {
        $trainings = Training::where('activity_id', $activityCRUD->id)->count(); // Miro si de ese Activity que quiero quitar hay algún Training asoiciado

        // Si no hay ningún training asociado con ese Activity, se borra y se devuelve un mensaje de éxito
        if ($trainings > 0) {
            // Vuelve a la página llamante con un mensaje de error
            return back()->with('status', 'Error: No se puede eliminar el Activity porque tiene trainings asociados'); 
        } else {
            // Elimina el activity y vuelve a la página llamante con un mensaje de éxito
            $activityCRUD->delete(); 
            return back()->with('status', 'Success: Activity eliminado correctamente'); 
        }
    }
}
