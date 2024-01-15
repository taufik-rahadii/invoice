<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ["id" => "e29ff1a3-4067-4e2c-9749-d4c9ed6696ea", "name" => "Superadmin", "code" => "superadmin"],
            ["id" => "8e085120-d044-414f-b4be-6a4496fd4684", "name" => "Admin", "code" => "admin"],
            ["id" => "ed9ae8e7-93ce-466e-a488-58735d7efc84", "name" => "Seller", "code" => 'seller'],
        ];

        DB::table('roles')->insert($roles);
    }
}
