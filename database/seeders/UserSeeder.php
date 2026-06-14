<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
        'name' => 'Jenni Rusli',
        'email' => 'jenni@ssifood.co.id',
        'password' => Hash::make('Jenni#!!'),
        'role' => 'sales',
        ]);

        User::create([
        'name' => 'Vanilla',
        'email' => 'vanilla@ssifood.co.id',
        'password' => Hash::make('Vanilla##!'),
        'role' => 'sales',
        ]);

        User::create([
        'name' => 'Tiffany',
        'email' => 'tiffany@ssifood.co.id',
        'password' => Hash::make('Tiffany@!!'),
        'role' => 'sales',
        ]);

        User::create([
            'name' => 'Legal',
            'email' => 'legal@ssifood.co.id',
            'password' => Hash::make('Legal##!'),
            'role' => 'legal',
        ]);

        User::create([
            'name' => 'R&D',
            'email' => 'research@ssifood.co.id',
            'password' => Hash::make('Research@#!'),
            'role' => 'r&d',
        ]);

        User::create([
            'name' => 'Production',
            'email' => 'production@ssifood.co.id',
            'password' => Hash::make('Production!@@'),
            'role' => 'production',
        ]);

        User::create([
            'name' => 'Purchasing',
            'email' => 'purchasing@ssifood.co.id',
            'password' => Hash::make('Purchasing!!$$'),
            'role' => 'purchasing',
        ]);

        User::create([
            'name' => 'Rian',
            'email' => 'rian@ssifood.co.id',
            'password' => Hash::make('Finance!!$$'),
            'role' => 'finance',
        ]);
    }
}
