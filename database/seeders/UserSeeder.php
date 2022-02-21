<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $names = ['Фёдор', 'Игорь', 'Анна'];
        $emails = ['fyodor.kazaryan@bk.ru', 'igor2020@mail.ru', 'anna@mail.ru'];
        for ($i = 0; $i < count($names); $i++) {
            \DB::table('users')
                ->insert([
                    'name' => $names[$i],
                    'email' => $emails[$i],
                    'password' => \Hash::make('12345678'),
                    'remember_token' => Str::random(10),
                ]);
        }
        User::find(1)->assignRole(Role::where('name', 'admin')->get());
        User::find(2)->assignRole(Role::where('name', 'installer')->get());
        User::find(3)->assignRole(Role::where('name', 'manager')->get());
    }
}
