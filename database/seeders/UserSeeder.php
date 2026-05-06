<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Atlas',  'email' => 'atlas@microagile.io'],
            ['name' => 'Nova',   'email' => 'nova@microagile.io'],
            ['name' => 'Zara',   'email' => 'zara@microagile.io'],
            ['name' => 'Orion',  'email' => 'orion@microagile.io'],
            ['name' => 'Luna',   'email' => 'luna@microagile.io'],
            ['name' => 'Axel',   'email' => 'axel@microagile.io'],
            ['name' => 'Sage',   'email' => 'sage@microagile.io'],
            ['name' => 'Finn',   'email' => 'finn@microagile.io'],
            ['name' => 'Ivy',    'email' => 'ivy@microagile.io'],
            ['name' => 'Coda',   'email' => 'coda@microagile.io'],
        ];

        foreach ($users as $user) {
            User::create([
                'name'     => $user['name'],
                'email'    => $user['email'],
                'password' => Hash::make('password'),
            ]);
        }
    }
}