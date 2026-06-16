<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\CourtSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\TrainingUserSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        try {
            // Seeders / JSON
            $this->command->info('Ejecutando Seeders...');
            $this->call(RoleSeeder::class);
            $this->call(UserSeeder::class);

            // Factories
            $this->command->info('Ejecutando Factories...');
            User::factory(100)->create();

            // Seeders / JSON (faltantes)
            $this->command->info('Ejecutando Seeders Restantes...');
            $this->call(CourtSeeder::class);
            $this->call(ActivitySeeder::class);

            // Otros Seeders
            $this->command->info('Completando Seeders ...');
            $this->call(TrainingUserSeeder::class);

        } catch (\Exception $e) {
            $this->command->error("Error durante la ejecución de los seeders/factories: " . $e->getMessage());
        }
    }
}
