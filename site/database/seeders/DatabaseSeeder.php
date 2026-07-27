<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Creates (or updates) the single admin user from the ADMIN_EMAIL and
     * ADMIN_PASSWORD environment variables. Nothing is seeded when either
     * is missing.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->command?->warn('ADMIN_EMAIL or ADMIN_PASSWORD not set — skipping admin user seeding.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Admin', 'password' => Hash::make($password)],
        );

        $this->command?->info("Admin user seeded for {$email}.");
    }
}
