<?php

    namespace App\Services\Classes;

    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Type;
    use App\Models\SystemVariables;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    class MosquitoSystemsCalculator extends BaseCalculator
    {
        use HasSquare;

        // todo: ремонт, срочное изготовление, коэффициент сложности монтажа

        /**
         * Type of mosquito system in current request
         *
         * @var Type
         */
        protected Type $type;

        /**
         * Collection of additional services or accessories in current request
         *
         * @var Collection
         */
        protected Collection $additional;

        /**
         * @var float
         */
        protected float $measuringSalary = 0.0;

        /**
         * @var bool
         */
        protected bool $needMeasuring;

        public function __construct(Request $request) {
            parent::__construct($request);

            dump($request->all());

            $this->needMeasuring = !$request->has('measuring') || $request->get('measuring');

            try {
                $this->type = Type::byCategory($request->get('categories'));
            } catch (\Exception $exception) {
                return view('welcome')->withErrors([
                    'not_found' => 'Тип не найден',
                    'message' => 'Информация для отладки: ' . $exception->getMessage(),
                ]);
            }

            $this->additional = new Collection();
        }

        public function calculate(): void {
            // todo сделать return (общая сумма всех слагаемых)
            // todo в таком случае для обновления заказа нужно не забыть что замер и доставка не должны учитываться
            // дважды


            /*
             * Making all preparations that are the same to all products
             */
            parent::calculate();

            /*
             * Firstly, need to calculate product's main price.
             * Then, calculating price of additional attributes.
             * Multiplying prices of additional and main product's price by count of products.
             * Delivery price, salary, measuring price etc. don't depend on count.
             * Because delivery doesn't depend on count and other order characteristics,
             * it's price must be added here. It had already been calculated in BaseCalculator.
             * Similarly with the price of measurement
             */
            $this->setProductPrice()
                ->calculatePriceForAdditional()
                ->setPriceForCount()
                ->addDelivery()
                ->addMeasuringPrice();

            /*
             * If we are needed in installation, measuring becomes free.
             */
            $this->checkMeasuringSale();

            /*
             * If we are needed in installation, constant sum of delivery
             * takes away from salary. But sum for additional km remains unchanged
             */
            $this->checkDeliverySalary();

            /*
             * Calculating salary based on list of additional items
             */
            $this->calculateSalary();

            $this->saveInstallationData();
        }

        /**
         * @return void
         */
        protected function checkDeliverySalary() {
            if (!$this->needInstallation) {
                $this->installersWage += SystemVariables::where('name', 'delivery')
                    ->first()
                    ->value;
            }
        }

        /**
         * @return Type
         */
        public function getType(): Type {
            return $this->type;
        }

        /**
         * @return Collection
         */
        public function getAdditional(): Collection {
            return $this->additional;
        }

        /**
         * @return float
         */
        public function getMeasuringSalary(): float {
            return $this->measuringSalary;
        }

        protected function setMeasuringPrice(): void {
            $measuring = SystemVariables::where('name', 'measuring')
                ->first(['value', 'description']);

            $measuringWage = SystemVariables::where('name', 'measuringWage')
                ->first(['value', 'description']);

            $this->measuringSalary += $measuringWage->value;

            $this->measuringPrice += $measuring->value;
        }

        protected function checkMeasuringSale() {
            dump([
               'need installation' => $this->needInstallation
            ]);
            if ($this->needInstallation) {
                $this->price -= $this->measuringPrice;
                $this->measuringPrice = 0;
                $this->measuringSalary = 0;
            }
        }

        protected function calculateDelivery(): void {
            dump([
                'need delivery' =>
                $this->needDelivery()
            ]);
            if (!$this->needDelivery()) {
                return;
            }

            $distance = (float)$this->request->get('kilometres');

            $additionalDistancePrice = SystemVariables::where('name', 'additionalPriceDeliveryPerKm')
                ->first();

            $this->deliveryPrice += $additionalDistancePrice->value * $distance;

            $this->deliveryPrice += $this->type->delivery;

            $additionalDistanceWage = SystemVariables::where('name', 'additionalWagePerKm')
                ->first();
            $salaryForAdditionalDelivery = $additionalDistanceWage->value * $distance;

            // Цена за доп. км. и зп за доставку за замер считаются отдельно от выбора "нужна доставка"
            if ($this->needMeasuring) {
                $this->deliveryPrice += $additionalDistancePrice->value * $distance;
                $salaryForAdditionalDelivery += $additionalDistanceWage->value * $distance;
            }

            $this->installersWage += $salaryForAdditionalDelivery;

            $this->saveDelivery($additionalDistancePrice->value * $distance * $this->count, $salaryForAdditionalDelivery);
        }

        /**
         * Product's main price (of one square meter) determines by the combination
         * of three parameters: tissue, type and profile.
         * It's model called Product.
         * That price multiplies by square - but if square <= 1, squareCoefficient = 1, else
         * squareCoefficient = square (in square meters)
         *
         * @return \Illuminate\Http\RedirectResponse|BaseCalculator
         */
        protected function setProductPrice() {
            try {
                $product = Product::where('tissue_id', $this->request->get('tissues'))
                    ->where('profile_id', $this->request->get('profiles'))
                    ->whereHas('type', function (Builder $query) {
                        $query->where('category_id', $this->request->get('categories'));
                    })
                    ->firstOrFail();

                $this->price += $product->price * $this->squareCoefficient;

                $this->savePrice($product->price * $this->squareCoefficient);

            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
                return back()->withErrors([
                    'not_found' => 'Такого товара не найдено',
                ]);
            }

            return $this;
        }

        /**
         * Secondary, need to calculate price of all additional options.
         * Additional is the service of the accessory, that may be added to product.
         * Additional attributes are separated on groups, such installation, bracing or color.
         * Products can have different count of additional attributes and
         * different count of groups of that attributes.
         * In the page, groups are marked from 1 to n as html select's names, and
         * values of additional ids as option's values.
         */
        protected function calculatePriceForAdditional() {
            $typeId = $this->type->id;

            $i = 1;
            $ids = [];
            while ($this->request->has("group-$i")) {
                $ids[] = $this->request->get("group-$i");
                $i++;
            }

            $additional = \DB::table('mosquito_systems_type_additional')
                ->where('type_id', $typeId)
                ->whereIn('additional_id', $ids)
                ->leftJoin('mosquito_systems_additional', 'additional_id', '=', 'mosquito_systems_additional.id')
                ->leftJoin('mosquito_systems_groups', 'group_id', '=', 'mosquito_systems_groups.id')
                ->get([
                    'price',
                    'additional_id',
                    'mosquito_systems_groups.name as group_name',
                    'mosquito_systems_additional.name',
                ]);

            $items = collect();
            $additionalCollection = collect();

            foreach ($additional as $item) {
                $additionalPrice = $item->price;

                if (!$this->additionalIsInstallation($item)) {
                    $additionalPrice *= $this->squareCoefficient;
                } else {
                    $this->installationPrice = $additionalPrice;
                }

                $items->push("Доп. за $item->name: $additionalPrice");
                $additionalCollection->push($item);

                $this->price += $additionalPrice ?? 0;
            }

            $this->saveAdditional($items);
            $this->additional->push($additionalCollection)->collapse();

            return $this;
        }

        /**
         * Salary for mosquito systems are depended on count of products,
         * it's type and kind of installation.
         * If there are no salary for specified count of products,
         * need to find salary for maximal count and add to it the product(*) of an
         * additional price for the number of items
         *
         * @return void
         */
        protected function calculateSalary() {

            if (!$this->needInstallation) {
                $this->installersWage += $this->measuringSalary;
            }

            $this->additional = $this->additional->collapse();
            foreach ($this->additional as $item) {
                if (!$this->additionalIsInstallation($item)) {
                    continue;
                }

                $salary = \DB::table('mosquito_systems_type_salary')
                    ->where('type_id', $this->type->id)
                    ->where('additional_id', $item->additional_id)
                    ->where('count', $this->count)
                    ->first();

                if ($salary) {
                    $this->installersWage += $salary->salary;
                } else {
                    $salary = \DB::table('mosquito_systems_type_salary')
                        ->where('type_id', $this->type->id)
                        ->where('additional_id', $item->additional_id)
                        ->orderByDesc('count')
                        ->first();
                    $this->installersWage += $salary->salary + $this->count * $salary->salary_for_count;
                }
            }
        }

        /**
         * Saving info to json
         *
         * @param Collection $additional
         * @return void
         */
        protected function saveAdditional(Collection $additional) {
            $this->options->put(
                'additional', $additional
            );
        }

        /**
         * Determines if additional belongs to installation group of services
         *
         * @param $additional
         * @return bool
         */
        protected
        function additionalIsInstallation($additional): bool {
            if ($additional->name == 'Без монтажа') {
                $this->needInstallation = false;
            }

            if (get_class($additional) != 'App\Models\MosquitoSystems\Additional') {
                if ($additional->group_name == 'Монтаж' && $additional->name != 'Без монтажа') {
                    $this->needInstallation = true;
//                    return true;
                }
                return $additional->group_name == 'Монтаж' && $additional->name != 'Без монтажа';
            } else {
                if ($additional->name != 'Без монтажа' && $additional->group->name == 'Монтаж') {
                    $this->needInstallation = true;
//                    return true;
                }
                return $additional->name != 'Без монтажа';
            }

//            return $this->needInstallation;
        }

        /**
         * @return Collection
         */
        public
        function getOptions(): Collection {
            return $this->options;
        }

        /**
         * @return bool
         */
        public function getNeedMeasuring(): bool {
            return $this->needMeasuring;
        }
    }
