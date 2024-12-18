<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->create([
            "name" => "أدمن",
            "email" => "admin@fadisarwat.dev",
            "username" => "admin",
            "password" => bcrypt("123456"),
            "is_admin" => true,
        ]);
    }
}
