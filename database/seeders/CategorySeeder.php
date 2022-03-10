<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    protected $superCategories = [
        ['name' => 'Москитные системы'],
        ['name' => 'Стеклопакеты'],
        ['name' => 'Подоконник'],
        ['name' => 'Другое'],
    ];

    protected $subCategories = [
        [
            'name' => 'Рамные москитные сетки',
            'parent_id' => 1,
        ],
        [
            'name' => 'Вставные сетки VSN',
            'parent_id' => 1,
        ],
        [
            'name' => 'Москитные двери',
            'parent_id' => 1,
        ],
        [
            'name' => 'Раздвижные сетки',
            'parent_id' => 1,
        ],
        [
            'name' => 'Рулонные сетки',
            'parent_id' => 1,
        ],
        [
            'name' => 'Сетки крыло',
            'parent_id' => 1,
        ],
        [
            'name' => 'Сетка трапециевидная',
            'parent_id' => 1,
        ],
        [
            'name' => 'Рамные москитные сетки',
            'parent_id' => 1,
        ],
        [
            'name' => 'Сетки плиссе Россия',
            'parent_id' => 1,
        ],
        [
            'name' => 'Сетки плиссе Италия',
            'parent_id' => 1,
        ],
        [
            'name' => 'Сетки AlumSN',
            'parent_id' => 1,
        ],
        [
            'name' => 'Стеклопакет',
            'parent_id' => 2,
        ],
        [
            'name' => 'Замена стекла на стеклопакет',
            'parent_id' => 2,
        ],
        [
            'name' => 'Стеклопакет с отверстием',
            'parent_id' => 2,
        ],
        [
            'name' => 'Стеклопакет с подогревом',
            'parent_id' => 2,
        ],
        [
            'name' => 'Стекло',
            'parent_id' => 2,
        ],
        [
            'name' => 'Подоконник',
            'parent_id' => 3,
        ],
        [
            'name' => 'Накладка на подоконник',
            'parent_id' => 3,
        ],
        [
            'name' => 'Ремонт\\Аксессуары\\Услуги',
            'parent_id' => 4,
        ],
        [
            'name' => 'Пленка на окно',
            'parent_id' => 4,
        ],
        [
            'name' => 'Отлив',
            'parent_id' => 4,
        ],
        [
            'name' => 'Откос',
            'parent_id' => 4,
        ],
        [
            'name' => 'Добавить позицию',
            'parent_id' => 4,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach ($this->superCategories as $superCategory) {
            \DB::table('categories')->insert($superCategory);
        }
        foreach ($this->subCategories as $subCategory) {
            \DB::table('categories')->insert($subCategory);
        }
    }
}
