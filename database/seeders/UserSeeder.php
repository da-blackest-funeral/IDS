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
            $names = [
                'Фёдор',
                'Игорь',
                'Анна',
                'Михаил',
                'Вера',
                'Дмитрий',
                'Владимир монтажник',
            ];

            $emails = [
                'fyodor.kazaryan@bk.ru',
                'igor2020@mail.ru',
                'anna@mail.ru',
                'mihail@mail.ru',
                'vera@mail.ru',
                'dima@mail.ru',
                'test@installer.ru',
            ];

            for ($i = 0; $i < count($names); $i++) {
                \DB::table('users')
                    ->insert([
                        'name' => $names[$i],
                        'email' => $emails[$i],
                        'password' => \Hash::make('12345678'),
                        'remember_token' => Str::random(10),
                    ]);
            }
            User::find(1)->assignRole(Role::findByName('admin'));
            User::find(2)->assignRole(Role::findByName('installer'));
            User::find(3)->assignRole(Role::findByName('manager'));
            User::find(4)->assignRole(Role::findByName('collector'));
            User::find(5)->assignRole(Role::findByName('evening_manager'));
            User::find(6)->assignRole(Role::findByName('director'));
            User::find(7)->assignRole(Role::findByName('installer'));
        }
    }
