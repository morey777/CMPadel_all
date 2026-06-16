<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserControllerCRUD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Devuelve todos los usuarios, mostrandolos en 4 en 4 y ordenado por fecha de actualización descendente
        $users = User::orderBy('updated_at', 'desc')->paginate(4);

        // Llamada a la View 'user.index' pasando $users para que me muestre todos los users
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(User $userCRUD)
    {
        return view('user.show',['user' => $userCRUD]);  // userCRUD/{userCRUD}
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $userCRUD)
    {
        $rols = Role::pluck('id','name');
        return view('user.edit',['user' => $userCRUD, 'rols' => $rols]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $userCRUD)
    {
        $userCRUD->update($request->all()); //Actualizamos el registro de la DDBB
        return redirect()->route('userCRUD.index')->with('status','Success: Usuario actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
