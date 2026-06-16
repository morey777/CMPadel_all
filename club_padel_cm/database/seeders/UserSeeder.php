<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = "Clara";
        $admin->lastname = "Morey";
        $admin->dni = "47324929F";
        $admin->email = "claram@cmpadel.com";
        $admin->phone = "472482698";
        $admin->password = Hash::make('12345678');
        $admin->role_id = Role::where('name', 'admin')->value('id');
        $admin->save(); 

        $jsonData = file_get_contents('database\jsons\users.json');
        $users = json_decode($jsonData, true);

        foreach ($users['usuarios']['usuario'] as $user) {
            User::updateOrCreate(
                [
                  'dni' => $user['dni']
                ],
                [
                  'name'     => $user['nombre'],
                  'lastname' => $user['apellido'],
                  'email' => $user['email'],
                  'phone' => $user['telefono'],
                  'password' => $user['password'],
                  'role_id' => Role::where('name', 'monitor')->value('id')
                ]
            );
            
        }
    }
}
