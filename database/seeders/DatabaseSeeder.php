<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DefaultSubscriptionsSeeder;
use Database\Seeders\PlanTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ici tu ajoutes tes seeders personnalisés
        $this->call([
            DefaultSubscriptionsSeeder::class,
            PlanTypeSeeder::class,
        ]);
    }
}
