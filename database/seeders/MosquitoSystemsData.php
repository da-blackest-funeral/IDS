<?php

namespace Database\Seeders;

use App\Models\MosquitoSystems\Product;
use JetBrains\PhpStorm\ArrayShape;

class MosquitoSystemsData
{
    #[ArrayShape(['tissues' => "array[]", 'groups' => "\string[][]", 'types' => "array[]", 'profiles' => "\string[][]", 'additional' => "array", 'products' => "\int[][]", 'product_additional' => "array"])]
    public static function all(string $key): array {

        /*
         * Concrete data about mosquito systems for database seeding
         * date: 10.03.2022
         */

        $default25Profile1Type = [6, 2, 1, 16, 13, 4, 3, 8, 9, 38, 39];

        $default32Profile1Type = [6, 2, 1, 13, 5, 3, 8, 9, 38, 39];

        // Default for Антикошка
        $defaultAntikoshkaTissues25Profile = [2, 1, 16, 13, 4, 3, 8, 9, 38, 39];
        $defaultAntikoshkaTissues32Profile = [2, 1, 13, 5, 3, 8, 9, 38, 39];

        $defaultProfile2Type = [21, 13, 3, 14, 41, 42, 38, 39];

        // Москитные двери 25 профиль
        $default25Profile3Type = [7, 13, 3, 4, 10, 14, 38, 39];

        // Москитные двери 32 профиль
        $default32Profile3Type = [7, 13, 3, 5, 10, 14, 38, 39];

        // Москитные двери 42 профиль
        $default42Profile3Type = [15, 13, 3, 11, 14, 38, 39];

        // Раздвижные сетки
        $default3Type = [3, 4, 12, 13, 14, 38, 39];

        // Рулонные сетки пр-во Россия
        $default4Type8Profile = [3, 22, 23, 13, 4, 12, 14];

        // Рулонные сетки пр-во Италия
        $default4Type9Profile = [3, 23, 13, 4, 12, 14];

        // Сетки крыло
        $default6Type = [17, 13, 3, 14, 38, 39];

        // Сетка трапециевидная
        $default7Type = [1, 2, 3, 13, 8, 9, 14, 19, 20, 38, 39];

        // Сетки плиссе Россия
        $default9Type = [33, 34, 35, 36, 3, 4, 13, 14, 29, ];

        // Сетки плиссе Италия
        $default5Type10Profile = [32, 24, 25, 26, 27, 3, 4, 13, 14, 28, 30, 31];
        $default5Type11Profile = [32, 24, 25, 26, 27, 3, 4, 13, 29];

        // Сетки AlumSN
        $default10Type = [21, 40, 14, 38, 39];

        // For default additional_id values of 25-profile Рамных москитных сеток
        foreach ([1, 2, 169, 110, 5, 86, 6, 60, 158, 7, 8, 77] as $product_id) {
            foreach ($default25Profile1Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 32-profile Рамных москитных сеток
        foreach ([9, 10, 170, 111, 13, 87, 14, 61, 159, 15, 78] as $product_id) {
            foreach ($default32Profile1Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of VSN
        foreach ([95, 96, 176, 121, 133, 132, 144, 97, 102, 98, 101, 167, 99, 100, 103] as $product_id) {
            foreach ($defaultProfile2Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 25-profile "Москитные двери"
        // for all there are the same additional values
        $productIds = Product::where('type_id', 2)
            ->where('profile_id', 1)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default25Profile3Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 32-profile "Москитные двери"
        $productIds = Product::where('type_id', 2)
            ->where('profile_id', 2)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default32Profile3Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 42-profile "Москитные двери"
        $productIds = Product::where('type_id', 2)
            ->where('profile_id', 3)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default42Profile3Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 25-profile "Рамные москитные сетки Антикошка"
        $productIds = Product::where('type_id', 1)
            ->where('profile_id', 1)
            ->whereIn('tissue_id', [3, 4, 13, 14])
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($defaultAntikoshkaTissues25Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of 32-profile "Рамные москитные сетки Антикошка"
        $productIds = Product::where('type_id', 1)
            ->where('profile_id', 2)
            ->whereIn('tissue_id', [3, 4, 13, 14])
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($defaultAntikoshkaTissues32Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of "Раздвижные сетки"
        $productIds = Product::where('type_id', 3)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default3Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of "Рулонные сетки пр-во Россия"
        $productIds = Product::where('type_id', 4)
            ->where('profile_id', 8)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default4Type8Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of "Рулонные сетки пр-во Россия"
        $productIds = Product::where('type_id', 4)
            ->where('profile_id', 9)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default4Type9Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of "Сетки крыло"
        $productIds = Product::where('type_id', 6)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default6Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For default additional_id values of "Сетка трапециевидная"
        $productIds = Product::where('type_id', 7)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default7Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For "Сетки плиссе Россия"
        $productIds = Product::where('type_id', 9)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default9Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        // For "Сетки плиссе Италия"
        $productIds = Product::where('type_id', 5)
            ->where('profile_id', 10)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default5Type10Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        $productIds = Product::where('type_id', 5)
            ->where('profile_id', 11)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default5Type11Profile as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        $productIds = Product::where('type_id', 10)
            ->get(['id'])
            ->pluck('id');

        foreach ($productIds as $product_id) {
            foreach ($default10Type as $additional_id) {
                $productAdditional[] = compact('product_id', 'additional_id');
            }
        }

        $all = [
            // Data about tissues
            'tissues' => [
                [
                    'name' => 'Антимоскит',
                    'link_page' => 'https://03-okna.ru/antimoskitnaya-setka/',
                    'description' => 'Антимоскитная сетка – рамная противомоскитная сетка с полотном “Антимоскит” (Fiberglass),
             которая прекрасно защищает дом от комаров и мух.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антимошка черная',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antimoshka-2/',
                    'description' => 'Москитная сетка “Антимошка” – рамная противомоскитная сетка со специальным полотном “Micro Mesh”,
            которая способна защитить Ваш дом не только от комаров, но и от мелких мошек.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антикошка черная',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antikoshka/',
                    'description' => 'Москитная сетка “Антимошка” – рамная противомоскитная сетка со специальным полотном “Micro Mesh”,
            которая способна защитить Ваш дом не только от комаров, но и от мелких мошек.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антикошка серая',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antikoshka/',
                    'description' => 'Москитная сетка “Антимошка” – рамная противомоскитная сетка со специальным полотном “Micro Mesh”,
            которая способна защитить Ваш дом не только от комаров, но и от мелких мошек.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антипыль полиэфир',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antipyl/',
                    'description' => 'Москитная сетка “Антипыль” – рамная противомоскитная сетка со специальным полотном,
             которая защищает ваш дом от насекомых, пыли, пуха и пыльцы, поступающих с улицы.',
                    'cut_width' => 1.5,
                ],
                [
                    'name' => 'Максимальный обзор',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-maksimalnyj-obzor-ultravyu/',
                    'description' => 'Москитная сетка “Максимальный обзор” – из названия видно, что такая сетка способна пропускать
             больше света и воздуха по сравнению с обычной москитной сеткой.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антиптица',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antiptica/',
                    'description' => 'Москитная сетка “Антиптица” – рамная алюминиевая сетка с металлическим полотном. Металлическое
             антикоррозийное полотно сетки способно противостоять клювам птиц, а также защищает от насекомых.',
                    'cut_width' => 1.5,
                ],
                [
                    'name' => 'Сетка с рисунком',
                    'link_page' => 'https://03-okna.ru/moskitnye-setki-s-risunkom/',
                    'description' => 'Москитные сетки с рисунком– это уникальная возможность украсить окна своего дома, выделить их из серой массы,
             придать им шарм. Также можно использовать сетки с рисунком в качестве рекламы.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Respilon Air',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-filtr-respilon-air/',
                    'description' => 'Сетка-фильтр Respilon Air – революция в мире москитных сеток.
             Чистый воздух, защита от пыли, комаров и смога в городской квартире 21 века.',
                    'cut_width' => 1.4,
                ],
                [
                    'name' => 'Антипыль Poll-tex',
                    'link_page' => '',
                    'description' => '',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Солнцезащитное полотно',
                    'link_page' => 'https://03-okna.ru/solncezashhitnaya-moskitnaya-setka/',
                    'description' => 'Солнцезащитная москитная сетка – рамная противомоскитная сетка,
            которая препятствует проникновению лишнего
            солнечного света в помещение, а также препятствует нагреву помещения под прямыми солнечными лучами.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антимошка серая',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-antimoshka-2/',
                    'description' => 'Москитная сетка “Антимошка” – рамная противомоскитная сетка со специальным
             полотном “Micro Mesh”, которая способна защитить Ваш дом не только от комаров, но и от мелких мошек.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Антикошка + антипыль',
                    'link_page' => '',
                    'description' => 'Москитная сетка антикошка -
            антипыль это сетка с комбинированным полотном, предназначена для защиты
            помещения от пыли и при этом стойкая к когтям кошек.',
                    'cut_width' => 0,
                ],
                [
                    'name' => 'Антикошка белая',
                    'description' => 'Москитная сетка “Антимошка” – рамная противомоскитная сетка со специальным полотном “Micro Mesh”,
            которая способна защитить Ваш дом не только от комаров, но и от мелких мошек.',
                    'cut_width' => 1.6,
                ],
                [
                    'name' => 'Оберег',
                    'link_page' => 'https://03-okna.ru/moskitnaya-setka-filtr-obereg/',
                    'description' => 'Сетка-фильтр Оберег - это запатентованный российский продукт
            высокого качества. Является аналогом сетки-фильтр Respilon (Чехия).
            Но за счет того, что производство находится в России, цена на это полотно для наших
            клиентов буде более привлекательная.',
                    'cut_width' => 1.4,
                ],
                [
                    'name' => 'Антимоскит белый',
                    'link_page' => '',
                    'description' => 'Москитная сетка (белая) - аналог стандартного антимоскитного полотна.
             Подойдет для клиентов, которые хотят исключить затемнение помещения.',
                    'cut_width' => 1,
                ],
            ],
            // Data about groups
            'groups' => [
                [
                    'name' => 'Крепление',
                ],
                [
                    'name' => 'Цвет',
                ],
                [
                    'name' => 'Монтаж',
                ],
                [
                    'name' => 'Дополнительные параметры',
                ],
                [
                    'name' => 'Тип окна',
                ],
                [
                    'name' => 'Вид углов'
                ]
            ],
            /*
             * Data about types, that are one-to-one related with categories, but contains
             * data only about mosquito systems
             */
            'types' => [
                [
                    'category_id' => 5,
                    'yandex' => 'Москитная сетка',
                    'page_link' => 'https://03-okna.ru/antimoskitnaya-setka',
                    'measure_link' => 'https://03-okna.ru/zamer-ramnyx-moskitnyx-setok/',
                    'salary' => 45,
                    'description' => 'Самый распространенный вид сеток.
             Можно устанавливать на окна, форточки и другие проемы,
             используя разные виды креплений (штоки, z-пластик, z-металл).',
                    'img' => '/calc/img/setka_antimoskit_1_8.png',
                    'measure_time' => 5,
                    'delivery' => 500,
                ],
                [
                    'category_id' => 7,
                    'delivery' => 800,
                    'yandex' => 'Москитная дверь',
                    'page_link' => 'https://03-okna.ru/moskitnye_dveri/',
                    'measure_link' => 'https://03-okna.ru/zamer-moskitnoj-dveri/',
                    'salary' => 60,
                    'description' => 'Москитная дверь используется для установки на балконную или входную дверь.
             В компании ОкнаПомощь можно заказать москитную дверь 25, 32, 42 или 52 профиля для решения разных задач.',
                    'img' => '/calc/img/dver_Antimoskit_1.png',
                    'measure_time' => 5,
                ],
                [
                    'category_id' => 8,
                    'delivery' => 800,
                    'yandex' => 'Раздвижная сетка',
                    'page_link' => 'https://03-okna.ru/razdvizhnye-moskitnye-setki/',
                    'measure_link' => 'https://03-okna.ru/zamer-razdvizhnyx-setok/',
                    'salary' => 65,
                    'description' => 'Раздвижные москитные сетки – специальный вид сеток,
             который устанавливается на раздвижные окна балкона или лоджии, а также
              на раздвижные двери, которые ведут на веранду или террасу.',
                    'img' => '/calc/img/razdv_Antimoskit_1.png',
                    'measure_time' => 8,
                ],
                [
                    'category_id' => 9,
                    'delivery' => 500,
                    'yandex' => 'Рулонная сетка',
                    'page_link' => 'https://03-okna.ru/rulonnye-moskitnye-setki/',
                    'measure_link' => 'https://03-okna.ru/zamer-rulonnoj-moskitnoj-setki/',
                    'description' => 'Рулонные москитные сетки – инновационный тип москитных сеток, который устанавливается
             на оконные и дверные проемы со стороны улицы, защищая дом от насекомых.',
                    'img' => '/calc/img/rulon_Antimoskit_1.png',
                    'measure_time' => 5,
                ],
                [
                    'category_id' => 13,
                    'delivery' => 500,
                    'yandex' => 'Сетка плиссе',
                    'page_link' => 'https://03-okna.ru/moskitnye-setki-plisse/',
                    'measure_link' => 'https://03-okna.ru/zamer-moskitnoj-setki-plisse/',
                    'description' => 'Итальянская москитная сетка-плиссе – изделие, которое по своей конструкции схоже с вертикальными жалюзи.
             Она складывается по принципу гармошки, двигаясь по алюминиевым направляющим.',
                    'img' => '/calc/img/plisse_1.png',
                    'measure_time' => 5,
                ],
                [
                    'category_id' => 10,
                    'delivery' => 500,
                    'yandex' => 'Сетка крыло',
                    'page_link' => 'https://03-okna.ru/moskitnye-setki-krylo/',
                    'measure_link' => 'https://03-okna.ru/zamer-moskitnoj-setki-krylo/',
                    'salary' => 55,
                    'description' => 'Особенностью данной сетки является небольшой выступ с наружной части изделия, напоминающий крыло.
             Благодаря этому каркас сетки плотно прилегает к оконной раме.',
                    'img' => '/calc/img/krilo_antimoskit_1_21.png',
                    'measure_time' => 5,
                ],
                [
                    'category_id' => 11,
                    'delivery' => 500,
                    'yandex' => 'Трапециевидная сетка',
                    'salary' => 75,
                    'description' => 'Нет описания',
                    'measure_time' => 10,
                ],
                [
                    'category_id' => 6,
                    'delivery' => 500,
                    'page_link' => 'https://03-okna.ru/vstavnaya-moskitnaya-setka-vsn/',
                    'measure_link' => 'https://03-okna.ru/zamer-vstavnoj-moskitnoj-setki-vsn/',
                    'salary' => 65,
                    'img' => '/calc/img/setkavsn_Antimoskit_1.png',
                    'measure_time' => 5,
                    'description' => 'Нет описания',
                ],
                [
                    'category_id' => 12,
                    'delivery' => 500,
                    'yandex' => 'Сетки плиссе производство Россия',
                    'page_link' => 'https://03-okna.ru/rossijskie-moskitnye-setki-plisse/',
                    'measure_link' => 'https://03-okna.ru/zamer-moskitnoj-setki-plisse/',
                    'description' => 'Российская москитная сетка-плиссе – изделие, которое по своей конструкции схоже с вертикальными жалюзи.
             Она складывается по принципу гармошки, двигаясь по алюминиевым направляющим.',
                    'img' => '/calc/img/plisse_1.png',
                    'measure_time' => 5,
                ],
                [
                    'category_id' => 14,
                    'yandex' => 'Сетки AlumSN',
                    'delivery' => 500,
                    'page_link' => 'https://03-okna.ru/vstavnaya-moskitnaya-setka-alumsn/',
                    'measure_link' => 'https://03-okna.ru/instrukciya-po-zameru-alumsn/',
                    'salary' => 60,
                    'description' => 'Вставная(внутренняя) москитная сетка AlumSN– это
            вид москитных сеток, разработанный и созданный специально для
            использования в алюминиевых окнах витражей и фасадов. Является аналогом москитных сеток VSN.',
                    'img' => '/wp-content/uploads/2022/03/алюмсн-1.png',
                    'measure_time' => 10,
                ],
            ],
            // Data about profiles
            'profiles' => [
                [
                    'name' => '25 профиль',
                ],
                [
                    'name' => '32 профиль',
                ],
                [
                    'name' => '42 профиль',
                ],
                [
                    'name' => '52 профиль',
                    'deleted_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'name' => 'Крыло',
                ],
                [
                    'name' => 'Профиль для раздв.',
                ],
                [
                    'name' => 'VSN',
                ],
                [
                    'name' => 'Пр-во Россия',
                ],
                [
                    'name' => 'Пр-во Италия',
                ],
                [
                    'name' => 'В проем',
                ],
                [
                    'name' => 'На раму',
                ],
                [
                    'name' => 'VSN усиленный',
                ],
                [
                    'name' => 'AlumSN',
                ],
            ],
            /*
             * Data for additional.
             * This values can be displayed as options in selects (groups)
             */
            'additional' => [
                [
                    'name' => 'Штоки',
                    'link' => 'https://03-okna.ru/moskitnye-setki-na-shtiftax/',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Z-крепления металл',
                    'link' => 'https://03-okna.ru/moskitnye-setki-na-z-krepleniyax/',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Коричневый цвет',
                    'group_id' => 2,
                ],
                [
                    'name' => 'Цвет по Ral 25 профиль',
                    'group_id' => 2,
                ],
                [
                    'name' => 'Цвет по Ral 32, 42, 52 профиль',
                    'group_id' => 2,
                ],
                [
                    'name' => 'Z-крепления пластик',
                    'link' => 'https://03-okna.ru/moskitnye-setki-na-z-krepleniyax/',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Петли и магниты',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Монтаж на Z-креплениях',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж на штоках',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж двери 25, 32 профиль',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж двери 42, 52 профиль',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Белый цвет',
                    'group_id' => 2,
                ],
                [
                    'name' => 'Без монтажа',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Петли с доводчиком',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Зацепы',
                    'link' => 'https://03-okna.ru/moskitnye-setki-na-zacepax/',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Крючки',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Монтаж на крючках',
                    'group_id' => 3,
                ],
                [
                    'name' => '2 прямых угла, 2 не прямых',
                    'group_id' => 6,
                ],
                [
                    'name' => 'Все углы не прямые',
                    'group_id' => 6,
                ],
                [
                    'name' => 'Не требуется',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Сворачивание вбок',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Сворачивание вверх',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Встречное открывание, ручка до середины',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Встречное открывание, ручка от края до края',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Реверсное открывание',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Вертикальное открывание',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Монтаж в проем',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж фронтальный',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж в проем с односторонним открыванием',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Монтаж в проем с двухсторонним открыванием',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Горизонтальное одностороннее открывание',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Открывание налево',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Открывание направо',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Открывание снизу вверх',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Открывание от центра в две стороны',
                    'group_id' => 1,
                ],
                [
                    'name' => 'Монтаж на зацепах',
                    'group_id' => 3,
                ],
                [
                    'name' => 'Пластиковые ручки',
                    'group_id' => 4,
                ],
                [
                    'name' => 'Металлические ручки',
                    'group_id' => 4,
                ],
                [
                    'name' => 'Серый цвет',
                    'group_id' => 2,
                ],
                [
                    'name' => 'Окна ПВХ или деревянные',
                    'group_id' => 5,
                ],
                [
                    'name' => 'Алюминиевые окна',
                    'group_id' => 5,
                ],
            ],
            /*
             * Data for products.
             * Product is a combination of 3 parameters - tissue_id, type_id, profile_id,
             * and it determines main price of product.
             */
            'products' => [
                [
                    'type_id' => 1,
                    'tissue_id' => 1,
                    'profile_id' => 1,
                    'price' => 880,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 2,
                    'profile_id' => 1,
                    'price' => 1400,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 3,
                    'profile_id' => 1,
                    'price' => 2140,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 4,
                    'profile_id' => 1,
                    'price' => 2240,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 5,
                    'profile_id' => 1,
                    'price' => 1940,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 6,
                    'profile_id' => 1,
                    'price' => 1400,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 7,
                    'profile_id' => 1,
                    'price' => 2690,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 8,
                    'profile_id' => 1,
                    'price' => 4460,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 1,
                    'profile_id' => 2,
                    'price' => 2050,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 2,
                    'profile_id' => 2,
                    'price' => 2610,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 3,
                    'profile_id' => 2,
                    'price' => 3330,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 4,
                    'profile_id' => 2,
                    'price' => 3430,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 5,
                    'profile_id' => 2,
                    'price' => 3310,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 6,
                    'profile_id' => 2,
                    'price' => 2560,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 7,
                    'profile_id' => 2,
                    'price' => 3970,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 1,
                    'profile_id' => 1,
                    'price' => 1350,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 2,
                    'profile_id' => 1,
                    'price' => 1890,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 3,
                    'profile_id' => 1,
                    'price' => 2380,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 4,
                    'profile_id' => 1,
                    'price' => 2420,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 5,
                    'profile_id' => 1,
                    'price' => 2070,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 6,
                    'profile_id' => 1,
                    'price' => 1850,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 7,
                    'profile_id' => 1,
                    'price' => 2170,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 1,
                    'profile_id' => 2,
                    'price' => 3090,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 2,
                    'profile_id' => 2,
                    'price' => 3430,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 3,
                    'profile_id' => 2,
                    'price' => 4010,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 4,
                    'profile_id' => 2,
                    'price' => 3960,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 5,
                    'profile_id' => 2,
                    'price' => 3810,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 6,
                    'profile_id' => 2,
                    'price' => 3500,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 7,
                    'profile_id' => 2,
                    'price' => 4020,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 1,
                    'profile_id' => 3,
                    'price' => 3330,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 2,
                    'profile_id' => 3,
                    'price' => 3830,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 3,
                    'profile_id' => 3,
                    'price' => 4360,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 4,
                    'profile_id' => 3,
                    'price' => 4390,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 5,
                    'profile_id' => 3,
                    'price' => 4350,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 6,
                    'profile_id' => 3,
                    'price' => 3830,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 7,
                    'profile_id' => 3,
                    'price' => 4350,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 1,
                    'profile_id' => 4,
                    'price' => 3510,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 1,
                    'profile_id' => 4,
                    'price' => 3510,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 2,
                    'profile_id' => 4,
                    'price' => 4050,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 3,
                    'profile_id' => 4,
                    'price' => 4540,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 4,
                    'profile_id' => 4,
                    'price' => 4580,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 5,
                    'profile_id' => 4,
                    'price' => 4500,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 6,
                    'profile_id' => 4,
                    'price' => 4040,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 7,
                    'profile_id' => 4,
                    'price' => 4550,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 1,
                    'profile_id' => 6,
                    'price' => 1800,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 2,
                    'profile_id' => 6,
                    'price' => 2250,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 3,
                    'profile_id' => 6,
                    'price' => 2740,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 4,
                    'profile_id' => 6,
                    'price' => 2780,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 5,
                    'profile_id' => 6,
                    'price' => 2430,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 6,
                    'profile_id' => 6,
                    'price' => 2250,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 7,
                    'profile_id' => 6,
                    'price' => 2700,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 1,
                    'profile_id' => 5,
                    'price' => 1390,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 2,
                    'profile_id' => 5,
                    'price' => 2050,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 3,
                    'profile_id' => 5,
                    'price' => 2640,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 4,
                    'profile_id' => 5,
                    'price' => 2870,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 5,
                    'profile_id' => 5,
                    'price' => 2560,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 6,
                    'profile_id' => 5,
                    'price' => 2080,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 7,
                    'profile_id' => 5,
                    'price' => 2960,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 8,
                    'profile_id' => 5,
                    'price' => 5110,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 9,
                    'profile_id' => 1,
                    'price' => 5380,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 9,
                    'profile_id' => 2,
                    'price' => 7090,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 9,
                    'profile_id' => 1,
                    'price' => 4770,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 9,
                    'profile_id' => 2,
                    'price' => 6670,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 9,
                    'profile_id' => 3,
                    'price' => 7280,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 9,
                    'profile_id' => 4,
                    'price' => 7560,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 9,
                    'profile_id' => 6,
                    'price' => 5310,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 9,
                    'profile_id' => 5,
                    'price' => 5990,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 1,
                    'profile_id' => 1,
                    'price' => 770,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 2,
                    'profile_id' => 1,
                    'price' => 1310,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 3,
                    'profile_id' => 1,
                    'price' => 1900,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 4,
                    'profile_id' => 1,
                    'price' => 2110,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 5,
                    'profile_id' => 1,
                    'price' => 1970,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 6,
                    'profile_id' => 1,
                    'price' => 1290,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 7,
                    'profile_id' => 1,
                    'price' => 2590,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 8,
                    'profile_id' => 1,
                    'price' => 4450,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 9,
                    'profile_id' => 1,
                    'price' => 6160,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 11,
                    'profile_id' => 1,
                    'price' => 2100,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 11,
                    'profile_id' => 2,
                    'price' => 3450,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 11,
                    'profile_id' => 1,
                    'price' => 2050,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 11,
                    'profile_id' => 2,
                    'price' => 3760,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 11,
                    'profile_id' => 3,
                    'price' => 4190,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 11,
                    'profile_id' => 4,
                    'price' => 4390,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 11,
                    'profile_id' => 6,
                    'price' => 2570,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 11,
                    'profile_id' => 5,
                    'price' => 2630,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 11,
                    'profile_id' => 1,
                    'price' => 2100,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 10,
                    'profile_id' => 1,
                    'price' => 2350,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 10,
                    'profile_id' => 2,
                    'price' => 3710,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 10,
                    'profile_id' => 1,
                    'price' => 2110,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 10,
                    'profile_id' => 2,
                    'price' => 3710,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 10,
                    'profile_id' => 3,
                    'price' => 4550,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 10,
                    'profile_id' => 4,
                    'price' => 4760,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 10,
                    'profile_id' => 6,
                    'price' => 2610,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 10,
                    'profile_id' => 5,
                    'price' => 2860,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 10,
                    'profile_id' => 1,
                    'price' => 2540,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 1,
                    'profile_id' => 7,
                    'price' => 1480,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 2,
                    'profile_id' => 7,
                    'price' => 2110,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 5,
                    'profile_id' => 7,
                    'price' => 2690,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 6,
                    'profile_id' => 7,
                    'price' => 2110,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 7,
                    'profile_id' => 7,
                    'price' => 3230,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 8,
                    'profile_id' => 7,
                    'price' => 4850,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 9,
                    'profile_id' => 7,
                    'price' => 6200,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 10,
                    'profile_id' => 7,
                    'price' => 3190,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 11,
                    'profile_id' => 7,
                    'price' => 2960,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 1,
                    'profile_id' => 8,
                    'price' => 5070,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 4,
                    'profile_id' => 8,
                    'price' => 6100,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 1,
                    'profile_id' => 9,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 2,
                    'profile_id' => 9,
                ],
                [
                    'type_id' => 5,
                    'tissue_id' => 1,
                    'profile_id' => 10,
                    'price' => 160,
                ],
                [
                    'type_id' => 5,
                    'tissue_id' => 1,
                    'profile_id' => 11,
                    'price' => 160,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 12,
                    'profile_id' => 1,
                    'price' => 1510,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 12,
                    'profile_id' => 2,
                    'price' => 2790,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 12,
                    'profile_id' => 1,
                    'price' => 3590,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 12,
                    'profile_id' => 2,
                    'price' => 3590,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 12,
                    'profile_id' => 3,
                    'price' => 4210,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 12,
                    'profile_id' => 4,
                    'price' => 4440,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 12,
                    'profile_id' => 6,
                    'price' => 2480,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 12,
                    'profile_id' => 9,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 12,
                    'profile_id' => 5,
                    'price' => 1710,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 12,
                    'profile_id' => 6,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 12,
                    'profile_id' => 1,
                    'price' => 1400,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 12,
                    'profile_id' => 7,
                    'price' => 2330,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 13,
                    'profile_id' => 1,
                    'price' => 3020,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 13,
                    'profile_id' => 2,
                    'price' => 4700,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 13,
                    'profile_id' => 1,
                    'price' => 2850,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 13,
                    'profile_id' => 2,
                    'price' => 5000,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 13,
                    'profile_id' => 3,
                    'price' => 5820,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 13,
                    'profile_id' => 4,
                    'price' => 5690,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 13,
                    'profile_id' => 6,
                    'price' => 4340,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 13,
                    'profile_id' => 5,
                    'price' => 3980,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 13,
                    'profile_id' => 1,
                    'price' => 3090,
                ],
                [
                    'type_id' => 9,
                    'tissue_id' => 1,
                    'profile_id' => 11,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 4,
                    'profile_id' => 7,
                    'price' => 2830,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 3,
                    'profile_id' => 7,
                    'price' => 2570,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 14,
                    'profile_id' => 1,
                    'price' => 2040,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 14,
                    'profile_id' => 2,
                    'price' => 3230,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 14,
                    'profile_id' => 1,
                    'price' => 2220,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 14,
                    'profile_id' => 3,
                    'price' => 4190,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 14,
                    'profile_id' => 2,
                    'price' => 3760,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 14,
                    'profile_id' => 4,
                    'price' => 4380,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 14,
                    'profile_id' => 6,
                    'price' => 2580,
                ],
                [
                    'type_id' => 4,
                    'tissue_id' => 14,
                    'profile_id' => 8,
                    'price' => 5950,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 14,
                    'profile_id' => 5,
                    'price' => 2670,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 14,
                    'profile_id' => 1,
                    'price' => 1910,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 14,
                    'profile_id' => 7,
                    'price' => 2630,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 1,
                    'profile_id' => 12,
                    'price' => 1780,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 2,
                    'profile_id' => 12,
                    'price' => 2410,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 3,
                    'profile_id' => 12,
                    'price' => 2870,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 4,
                    'profile_id' => 12,
                    'price' => 3130,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 5,
                    'profile_id' => 12,
                    'price' => 2990,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 6,
                    'profile_id' => 12,
                    'price' => 2410,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 7,
                    'profile_id' => 12,
                    'price' => 3530,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 8,
                    'profile_id' => 12,
                    'price' => 5150,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 9,
                    'profile_id' => 12,
                    'price' => 6500,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 10,
                    'profile_id' => 12,
                    'price' => 3490,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 11,
                    'profile_id' => 12,
                    'price' => 3290,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 12,
                    'profile_id' => 12,
                    'price' => 2630,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 14,
                    'profile_id' => 12,
                    'price' => 2930,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 15,
                    'profile_id' => 1,
                    'price' => 5080,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 15,
                    'profile_id' => 2,
                    'price' => 6790,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 15,
                    'profile_id' => 1,
                    'price' => 4470,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 15,
                    'profile_id' => 2,
                    'price' => 6370,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 15,
                    'profile_id' => 3,
                    'price' => 6980,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 15,
                    'profile_id' => 4,
                    'price' => 7260,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 15,
                    'profile_id' => 6,
                    'price' => 5010,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 15,
                    'profile_id' => 5,
                    'price' => 5690,
                ],
                [
                    'type_id' => 7,
                    'tissue_id' => 15,
                    'profile_id' => 1,
                    'price' => 5860,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 15,
                    'profile_id' => 7,
                    'price' => 5900,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 15,
                    'profile_id' => 12,
                    'price' => 6200,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 16,
                    'profile_id' => 1,
                    'price' => 950,
                ],
                [
                    'type_id' => 1,
                    'tissue_id' => 16,
                    'profile_id' => 2,
                    'price' => 2150,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 16,
                    'profile_id' => 1,
                    'price' => 1430,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 16,
                    'profile_id' => 2,
                    'price' => 3200,
                ],
                [
                    'type_id' => 2,
                    'tissue_id' => 16,
                    'profile_id' => 3,
                    'price' => 3450,
                ],
                [
                    'type_id' => 3,
                    'tissue_id' => 16,
                    'profile_id' => 6,
                    'price' => 1890,
                ],
                [
                    'type_id' => 6,
                    'tissue_id' => 16,
                    'profile_id' => 5,
                    'price' => 1460,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 16,
                    'profile_id' => 7,
                    'price' => 1550,
                ],
                [
                    'type_id' => 8,
                    'tissue_id' => 16,
                    'profile_id' => 12,
                    'price' => 1900,
                ],
                [
                    'type_id' => 10,
                    'tissue_id' => 1,
                    'profile_id' => 13,
                    'price' => 1200,
                ],
            ],
            // Pivot table for products and additional
            'product_additional' => $productAdditional,

        ];

        return $all[$key];

    }
}
