<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::truncate(); //清空資料庫
        \App\Models\User::create([
            'name' => '王老師',
            'title' => '系統管理員',
            'username' => 'wang',
            'password' => bcrypt('wang1026'),
            'system_admin' => 1,
            'login_type' => 'local',
            
        ]);
    }
}
