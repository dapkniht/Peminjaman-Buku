<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'nisn' => '87213826',
            'kelas' => '2A',
            'password' => Hash::make('user')
        ]);

        Role::create(['name' => 'member']);

        $user->assignRole('member');
    }
}
