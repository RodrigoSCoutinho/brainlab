<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Database\Seeders\PortugueseLanguageQuestionsSeeder;
use Database\Seeders\MathQuestionsSeeder;
use Database\Seeders\EthicsCitizenshipQuestionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin account
        User::firstOrCreate(
            ['email' => 'admin@brainlab.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Professor account
        User::firstOrCreate(
            ['email' => 'prof@brainlab.com'],
            [
                'name'     => 'Professor Teste',
                'password' => Hash::make('password'),
                'role'     => 'professor',
            ]
        );

        // Portuguese language questions for the subject bank
        $this->call(PortugueseLanguageQuestionsSeeder::class);

        // Mathematics questions for the subject bank
        $this->call(MathQuestionsSeeder::class);

        // Ethics and Citizenship questions for the subject bank
        $this->call(EthicsCitizenshipQuestionsSeeder::class);
    }
}

