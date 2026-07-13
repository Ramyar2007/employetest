<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->syncRoles([$adminRole]);

        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            ['name' => 'Staff', 'password' => Hash::make('password')]
        );
        $staff->syncRoles([$staffRole]);

        $employees = collect(['Aland Rahim', 'Sara Hussein', 'Karwan Ali', 'Halgurd Omar'])
            ->map(fn (string $name) => Employee::firstOrCreate(['name' => $name]));

        if (Payout::count() === 0) {
            $tasks = [
                ['task' => 'Landing page redesign', 'amount' => 250000, 'status' => 'completed'],
                ['task' => 'API integration', 'amount' => 400000, 'status' => 'processing'],
                ['task' => 'Bug fixes - checkout flow', 'amount' => 150000, 'status' => 'pending'],
                ['task' => 'Monthly ad video batch', 'amount' => 600000, 'status' => 'completed'],
            ];

            foreach ($tasks as $i => $data) {
                Payout::create([...$data, 'employee_id' => $employees[$i % $employees->count()]->id]);
            }
        }
    }
}
