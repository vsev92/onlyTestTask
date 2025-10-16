<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Алексеев А. А.',
            'Борисов Б. Б.',
            'Владимиров В. В.',
            'Герасимов Г. Г.',
            'Демин Д. Д.',
            'Евгеньев Е. Е.',
            'Жуков Г. К.',
            'Заруцкий Н. В.',
            'Игнатьев И. И.',
            'Клименко К. К.'
        ];

        collect($names)->each(function ($name) {
            User::factory()->create([
                'name' => $name,
                'email' => Str::slug($name, '_') . '@example.com',
                'password' => bcrypt('password'),
            ]);
        });
    }
}
