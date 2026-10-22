<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Run: php artisan db:seed
     * Or:  php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        $this->call([
            ReferenceDataSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
