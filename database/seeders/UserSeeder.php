<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(['email' => 'c01@bk.ru'], [
            'name' => 'c01',
            'email' => 'c01@bk.ru',
            'email_verified_at' => now(),
            'password' => bcrypt('000000')
        ]);
        $user->profile()->updateOrCreate([
            'role' => UserRoleEnum::ADMIN
        ]);
    }
}
