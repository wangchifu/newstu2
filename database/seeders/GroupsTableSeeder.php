<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Group::truncate(); //清空資料庫
        \App\Models\Group::create([
            'name' => '彰化區',
        ]);
        \App\Models\Group::create([
            'name' => '和美區',
        ]);
        \App\Models\Group::create([
            'name' => '鹿港區',
        ]);
        \App\Models\Group::create([
            'name' => '二林區',
        ]);
        \App\Models\Group::create([
            'name' => '田中區',
        ]);
        \App\Models\Group::create([
            'name' => '北斗區',
        ]);
        \App\Models\Group::create([
            'name' => '員林區',
        ]);
        \App\Models\Group::create([
            'name' => '溪湖區',
        ]);
        \App\Models\Group::create([
            'name' => '國中A區',
        ]);
        \App\Models\Group::create([
            'name' => '國中B區',
        ]);
    }
}
