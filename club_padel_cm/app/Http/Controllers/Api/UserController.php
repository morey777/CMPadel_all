<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $monitor = Role::find($user->role_id);
            // echo $monitor->name . "\n";
            if ($monitor->name == "monitor") {
                $user->load([
                    'trainings',
                    'trainings.court',
                    'trainings.court.courtType',
                    'trainings.court.zoneType',
                    'trainings.activity',
                    'trainings.users_nm'
                ]); 
            }

            return (new UserResource($user))->additional(['meta' => 'Usuario mostrado correctamente']);
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
    public function update(UserRequest $request, User $user)
    {
        try {
            $user->update($request->all());
            return (new UserResource($user))->additional(['meta' => 'Usuario mostrado correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al tractar los datos',
                'error_details' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
