<?php

    namespace Database\Seeders;

    class GlazedWindowsData
    {
        public static function all($name) {

            $newGlazedWindows = $newGlazedWindows2 = [];
            $glazed_windows = [
                [
                    'layer_id' => 1,
                    'name' => '4 мм',
                ],
                [
                    'layer_id' => 1,
                    'name' => '5 мм',
                    'price' => 775,
                ],
                [
                    'layer_id' => 1,
                    'name' => '6 мм',
                    'price' => 949,
                ],
                [
                    'layer_id' => 1,
                    'name' => '4 И',
                    'price' => 1248,
                ],
                [
                    'layer_id' => 1,
                    'name' => '6 И',
                    'price' => 2028,
                ],
                [
                    'layer_id' => 1,
                    'name' => '6 мм (3.3.1)',
                    'price' => 3060,
                ],
                [
                    'layer_id' => 1,
                    'name' => '8 мм (4.4.1)',
                    'price' => 3660,
                ],
                [
                    'layer_id' => 2,
                    'name' => '6 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '7 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '8 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '9 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '10 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '11 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '12 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '14 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '15 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '16 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '17 мм',
                ],
                [
                    'layer_id' => 2,
                    'name' => '18 мм',
                    'price' => 60,
                ],
                [
                    'layer_id' => 2,
                    'name' => '19 мм',
                    'price' => 90,
                ],
                [
                    'layer_id' => 2,
                    'name' => '20 мм',
                    'price' => 120,
                ],
                [
                    'layer_id' => 2,
                    'name' => '21 мм',
                    'price' => 150,
                ],
                [
                    'layer_id' => 2,
                    'name' => '22 мм',
                    'price' => 180,
                ],
                [
                    'layer_id' => 2,
                    'name' => '24 мм',
                    'price' => 240,
                ],
                [
                    'layer_id' => 1,
                    'name' => '10 мм (5.5.1)',
                    'price' => 6530,
                ],
                [
                    'layer_id' => 1,
                    'name' => '12 мм (6.6.1)',
                    'price' => 6784,
                ],
                [
                    'layer_id' => 1,
                    'name' => '8 мм (4.4.2)',
                    'price' => 5730,
                ],
            ];
            $i = 0;

            foreach ($glazed_windows as $glazedWindow) {
                $newGlazedWindows[$i] = $glazedWindow;
                $newGlazedWindows2[$i] = $glazedWindow;
                $newGlazedWindows[$i]['category_id'] = 16;
                $newGlazedWindows2[$i]['category_id'] = 15;
                $i++;
            }

            $glazed_windows = array_merge($newGlazedWindows2, $newGlazedWindows);

            dump($glazed_windows);

//        var_dump($glazedWindow);

            $all = [
                'types_windows' => [
                    [
                        'name' => 'Окна ПВХ',
                    ],
                    [
                        'name' => 'Алюминиевые Окна',
                    ],
                    [
                        'name' => 'Деревянные окна (стеклопакет)',
                    ],
                ],

                'glazed_windows_layers' => [
                    [
                        'name' => 'Стекло',
                    ],
                    [
                        'name' => 'Камера',
                    ],

                ],

                'glazed_windows' => $glazed_windows,
                'glazed_windows_additional' => [
                    [
                        'option_name' => 'Без закалки',
                        'group' => 'forging',
                        'price' => 0,
                        'layer_id' => 1,
                    ],
                    [
                        'option_name' => 'Нужна закалка',
                        'group' => 'forging',
                        'price' => 1392,
                        'layer_id' => 1,
                    ],
                    [
                        'option_name' => 'Без аргона',
                        'group' => 'argon',
                        'price' => 0,
                        'layer_id' => 2,
                    ],
                    [
                        'option_name' => 'Нужен аргон',
                        'group' => 'argon',
                        'price' => 144,
                        'layer_id' => 2,
                    ],
                    [
                        'option_name' => 'Алюм. рамка',
                        'group' => 'frame',
                        'price' => 0,
                        'layer_id' => 2,
                    ],
                    [
                        'option_name' => 'Пластик. рамка',
                        'group' => 'frame',
                        'price' => 420,
                        'layer_id' => 2,
                    ],
                ],
            ];

            return $all[$name];
        }
    }
