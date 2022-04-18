<?php

    namespace Tests\Feature\Orders\MosquitoSystems;

    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Type;
    use App\Models\Order;
    use App\Models\ProductInOrder;
    use App\Models\Salaries\InstallerSalary;
    use App\Models\SystemVariables;
    use App\Models\User;

    class TestHelper
    {
        public function measuringPrice() {
            return SystemVariables::value('measuring');
        }

        public function createDefaultOrder(
            int $price = 2256,
            int $measuringPrice = 600,
            int $count = 1
        ) {
            Order::create([
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => $price, // todo переписать с учетом минимальной суммы заказа
                'installer_id' => 2,
                'discounted_price' => 2256, // todo поменять когда я сделаю учет скидок
                'status' => 0,
                'measuring_price' => $measuringPrice,
                'measuring' => 1,
                'discounted_measuring_price' => $measuringPrice, // todo скидки
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => $count,
                'taken_sum' => 0,
                'installing_difficult' => 1, // todo зачем это поле в таблице заказов? по идее оно не нужно
                'is_private_person' => 0,
                'structure' => 'not ready',
            ]);
        }

        public function installationPrice($typeId = 1, $installationId = 8) {
            return \DB::table('mosquito_systems_type_additional')
                ->where('additional_id', $installationId)
                ->where('type_id', $typeId)
                ->first()
                ->price;
        }

        public function defaultSalarySum(int $count, $typeId = 1, $installationId = 8) {
            return \DB::table('mosquito_systems_type_salary')
                ->where('type_id', $typeId)
                ->where('additional_id', $installationId)
                ->where('count', $count)
                ->first()
                ->salary;
        }

        public function productPrice($tissueId = 1, $profileId = 1, $typeId = 1) {
            return Product::whereTissueId($tissueId)
                ->whereProfileId($profileId)
                ->whereTypeId($typeId)
                ->first('price')
                ->price;
        }

        public function defaultDeliverySum($typeId = 1) {
            return Type::where('id', $typeId)
                ->first('delivery')
                ->delivery;
        }

        public function createDefaultOrderAndProduct() {

            $this->createDefaultOrder();

            $this->createDefaultProduct();

            $this->createDefaultSalary();
        }

        public function createDefaultProduct(int $installationId = 14, $coefficient = 1) {
            $data = '{
                    "size": {
                        "width": "1000",
                        "height": "1000"
                    },
                    "salary": 960,
                    "group-1": 6,
                    "group-2": 13,
                    "group-3": 14,
                    "group-4": 38,
                    "category": 5,
                    "delivery": {
                        "additional": 0,
                        "deliveryPrice": ' . $this->defaultDeliverySum() . ',
                        "additionalSalary": "Нет"
                    },
                    "tissueId": 1,
                    "measuring": ' . $this->measuringPrice() . ',
                    "profileId": 1,
                    "additional": [
                        {
                            "text": "Доп. за Z-крепления пластик: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Белый цвет: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Без монтажа: 0",
                            "price": 0
                        },
                        {
                            "text": "Доп. за Пластиковые ручки: 0",
                            "price": 0
                        }
                    ],
                    "main_price": ' . $this->productPrice() . ',
                    "coefficient": ' . $coefficient . ',
                    "installationPrice": 0
                }';

            ProductInOrder::create([
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => $installationId,
                'data' => $data,
            ]);
        }

        public function createDefaultSalary(int $sum = 960) {
            InstallerSalary::create([
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => $sum,
                'created_user_id' => 1,
                'comment' => '123',
                'status' => 1,
                'changed_sum' => 1100,
                'type' => '123',
            ]);
        }

        public function exampleMosquitoSystemsInputs(): array {
            return [
                'height' => 1000,
                'width' => 1000,
                'count' => 1,
                'categories' => 5,
                'tissues' => 1,
                'profiles' => 1,
                'group-1' => 6,
                'group-2' => 13,
                'group-3' => 14,
                'group-4' => 38,
                'add-mount-tools' => 0,
                'coefficient' => 1,
                'new' => 1,
                'fast' => 0,
                'comment' => 'Test Comment!',
            ];
        }

        public function defaultOrder() {
            return [
                'user_id' => 1,
                'delivery' => 600,
                'installation' => 0,
                'price' => 2361.6, // todo переписать с учетом минимальной суммы заказа
                'installer_id' => 2,
//                'discounted_price' => 2256, // todo поменять когда я сделаю учет скидок
                'status' => 0,
                'measuring_price' => 600,
//                'discounted_measuring_price' => 600, // todo скидки
                'comment' => 'Test Comment!',
                'service_price' => 0,
                'sum_after' => 0,
                'products_count' => 1,
                'taken_sum' => 0,
                'installing_difficult' => 1, // todo зачем это поле в таблице заказов? по идее оно не нужно
                'is_private_person' => 0,
            ];
        }

        public function defaultProductInOrder() {
            return [
                'order_id' => 1,
                'user_id' => 1,
                'category_id' => 5,
                'name' => 'Рамные москитные сетки, 25 профиль, полотно Антимоскит',
                'count' => 1,
                'installation_id' => 14,
                // todo сделать проверку data, возможно через assertTrue
            ];
        }

        public function defaultSalary() {
            return [
                'installer_id' => 2,
                'order_id' => 1,
                'category_id' => 5,
                'sum' => 960,
                'created_user_id' => 1,
            ];
        }
    }