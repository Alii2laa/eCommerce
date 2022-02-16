<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Ali Alaa',
            'email' => 'admin@molla.com',
            'password' => bcrypt('123456')
        ]);
    }
}
