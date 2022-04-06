<?php

    namespace App\Services\Classes;

    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Type;
    use App\Models\ProductInOrder;
    use App\Models\SystemVariables;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    class MosquitoSystemsCalculator extends BaseCalculator
    {
        use HasSquare;

        // todo: коэффициент сложности монтажа, скидки
        // todo вынести все методы по сохранению в options в другой класс
        // возможно в тот же класс куда я добавлю функции, т.е. фасад

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
        protected bool $needMeasuring = true;

        /**
         * Instance of current product
         *
         * @var Product
         */
        protected Product $product;

        protected float $coefficient = 1.0;

        public function __construct(Request $request) {
            parent::__construct($request);

            try {
                $this->product = Product::where('tissue_id', $this->request->get('tissues'))
                    ->where('profile_id', $this->request->get('profiles'))
                    ->whereHas('type', function (Builder $query) {
                        $query->where('category_id', $this->request->get('categories'));
                    })
                    ->firstOrFail();
            } catch (\Exception $exception) {
                return view('welcome')->withErrors([
                    'not_found' => 'Товар не найден',
                    'message' => 'Информация для отладки: ' . $exception->getMessage(),
                ]);
            }

            try {
                $this->type = Type::byCategory($request->get('categories'));
            } catch (\Exception $exception) {
                return view('welcome')->withErrors([
                    'not_found' => 'Тип не найден',
                    'message' => 'Информация для отладки: ' . $exception->getMessage(),
                ]);
            }

            if ($this->hasCoefficient()) {
                $this->coefficient = $this->request->get('coefficient');
            }

            $this->additional = new Collection();

            $this->saveTissue();
            $this->saveProfile();
        }

        protected function saveTissue() {
            $this->options->put('tissueId', $this->product->tissue_id);
        }

        protected function saveProfile() {
            $this->options->put('profileId', $this->product->profile_id);
        }

        public function calculate(): void {
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
            $this->calculateInstallationSalary();

            if ($this->hasCoefficient()) {
                $this->salaryForDifficulty();
            }

            $this->saveInstallationData();
        }

        protected function hasCoefficient() {
            return $this->request->has('coefficient') && $this->request->get('coefficient') != 1;
        }

        protected function salaryForDifficulty($salary = null) {
            $additionalSalary = (int) ceil ((
                $this->installationPrice -
                $this->installationPrice / $this->coefficient
            ) * (float) SystemVariables::value('coefficientSalaryForDifficult'));

            if (!is_null($salary)) {
                return $salary + $additionalSalary;
            }

            $this->installersWage += $additionalSalary;

            $this->options->put('salaryForCoefficient', "Доп. зарплата за коэффициент сложности: $additionalSalary");
        }

        /**
         * @return void
         */
        protected function checkDeliverySalary() {
            if (!$this->needInstallation) {
                $this->installersWage += SystemVariables::value('delivery');
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

        /**
         * @return \Illuminate\Database\Eloquent\Model
         */
        public function getProduct(): \Illuminate\Database\Eloquent\Model {
            return $this->product;
        }

        protected function setMeasuringPrice(): void {
            $measuring = SystemVariables::byName('measuring');

            $measuringWage = SystemVariables::byName('measuringWage');

            $this->measuringSalary += $measuringWage->value;

            $this->measuringPrice += $measuring->value;
        }

        protected function checkMeasuringSale() {
            if ($this->needInstallation) {
                $this->price -= $this->measuringPrice;
                $this->measuringPrice = 0;
                warning('Замер бесплатный при заключении договора!');
                $this->measuringSalary = 0;
            }
        }

        protected function calculateDelivery(): void {
            if (!$this->needDelivery()) {
                return;
            }

            $distance = (float)$this->request->get('kilometres');

            $additionalDistancePrice = SystemVariables::byName('additionalPriceDeliveryPerKm');

            $this->deliveryPrice += $additionalDistancePrice->value * $distance;

            $this->deliveryPrice += $this->type->delivery;

            $additionalDistanceWage = SystemVariables::byName('additionalWagePerKm');
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
                // Decreasing price if customer need repairing instead new
                if ($this->request->has('new') && !$this->request->get('new')) {
                    $this->product->price *= SystemVariables::value('repairCoefficient');
                }
                // Increasing price if customer need product to be created faster
                if ($this->request->has('fast') && $this->request->get('fast')) {
                    $this->product->price *= SystemVariables::value('coefficientFastCreating');
                }

                $this->price += $this->product->price * $this->squareCoefficient;

                $this->savePrice($this->product->price * $this->squareCoefficient);

            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
                return back()->withErrors([
                    'not_found' => 'Такого товара не найдено',
                ]);
            }

            return $this;
        }

        protected function getSelectedIds() {
            $i = 1;
            $ids = [];
            while ($this->request->has("group-$i")) {
                $ids[] = $this->request->get("group-$i");
                $i++;
            }

            return $ids;
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
            $ids = $this->getSelectedIds();

            $additional = $this->getTypeAdditional($ids)
                // Writing to options selected groups
                ->each(function ($option) {
                    $this->options->put("group-$option->group_id", $option->additional_id);
                });

            // Items will be displayed to user
            $items = new Collection();
            $additionalCollection = new Collection();

            foreach ($additional as $add) {
                $additionalPrice = $add->price;

                if (!$this->additionalIsInstallation($add)) {
                    $additionalPrice *= $this->squareCoefficient;
                } else {
                    // If customer need fast creating, installation price increases
                    if ($this->request->has('fast') && $this->request->get('fast')) {
                        $additionalPrice *= SystemVariables::value('coefficientFastCreating');
                    }

                    $this->installationPrice = $additionalPrice;

                    if ($this->hasCoefficient()) {
                        $this->installationPrice *= $this->coefficient;
                        $additionalPrice *= $this->coefficient;
                    }
                }

                $items->push([
                    'text' => "Доп. за $add->name: " . $additionalPrice * $this->count,
                    'price' => $additionalPrice * $this->count,
                ]);
                $additionalCollection->push($add);

                $this->price += $additionalPrice ?? 0;
            }

            if ($this->hasCoefficient()) {
                $items->push([
                    'text' => 'Доп. цена за сложность монтажа: ' . $this->installationPrice -
                        $this->installationPrice / $this->coefficient,
                    'price' => 0,
                ]);
            }

            $this->saveAdditional($items);
            $this->additional->push($additionalCollection)->collapse();

            return $this;
        }

        protected function getTypeAdditional($ids) {
            return \DB::table('mosquito_systems_type_additional')
                ->where('type_id', $this->type->id)
                ->whereIn('additional_id', $ids)
                ->leftJoin('mosquito_systems_additional', 'additional_id', '=', 'mosquito_systems_additional.id')
                ->leftJoin('mosquito_systems_groups', 'group_id', '=', 'mosquito_systems_groups.id')
                ->get([
                    'price',
                    'additional_id',
                    'mosquito_systems_groups.name as group_name',
                    'mosquito_systems_groups.id as group_id',
                    'mosquito_systems_additional.name',
                ]);
        }

        /**
         * Salary for mosquito systems are depended on count of products,
         * it's type and kind of installation.
         * If there are no salary for specified count of products,
         * need to find salary for maximal count and add to it the product(*) of an
         * additional price for the number of items
         *
         * @return float|null
         */

        public function calculateInstallationSalary(): float|null {

            if (!$this->needInstallation) {
                $this->installersWage += $this->measuringSalary;
            }

            $this->additional = $this->additional->collapse();

            foreach ($this->additional as $item) {
                if (!$this->additionalIsInstallation($item)) {
                    continue;
                }

                $salary = $this->getInstallationSalary($item);

                if ($salary !== null) {
                    $this->installersWage += $salary->salary;
                } else {
                    $salary = $this->salaryWhenNotFoundSpecificCount($item);

                    $this->installersWage += $salary->salary + ($this->count - $salary->count) * $salary->salary_for_count;
                }
            }

            return $this->installersWage;
        }

        public function calculateSalaryForCount(int $count, ProductInOrder $productInOrder) {
            if (
                $productInOrder->installation_id == 0 &&
                !$this->installation->additional_id &&
                !$this->installation->id
            ) {
                return $this->installersWage;
            }

            $installation = $productInOrder->installation_id > 0 && $productInOrder->installation_id != 14 ?
                $productInOrder->installation_id :
                $this->installation->additional_id ?? $this->installation->id;

            $salary = $this->getInstallationSalary(
                $installation,
                $count
            );

            if ($salary != null) {
                $result = $salary->salary;
            } else {
                // todo при добавлении нового товара сюда код даже не заходит и считает зарплату неправильно
                $salary = $this->salaryWhenNotFoundSpecificCount($installation);
                // todo количество на которое умножаем должно считаться так:
                // общее количество товаров этого типа в заказе - salary->count
                // todo если добавить другой товар, которого еще не было, то
                // считается общее количество ВСЕХ товаров (а не конкретного типа) и зарплата не прибавляется
                // а заменяется этим значением

                // Если это страница обновления товара
                if ($this->request->has('product_id')) {
                    $missingCount = $this->request->get('count') - $salary->count;
                } else {
                    $missingCount = productsCount($productInOrder) - $salary->count;
                }

                $result = $salary->salary + $missingCount * $salary->salary_for_count;
            }

//            $this->setSalaryForInstallationDifficult();

//            return $this->setSalaryForInstallationDifficult($result);
            if ($this->hasCoefficient()) {
                return $this->salaryForDifficulty($result);
            } else {
                return $result;
            }
        }

        protected function getInstallationSalary($installation, $count = null) {
            return \DB::table('mosquito_systems_type_salary')
                ->where('type_id', $this->type->id)
                ->where('additional_id', $installation->additional_id ?? $installation)
                ->where('count', $count ?? $this->count)
                ->first();
        }

        protected function salaryWhenNotFoundSpecificCount($installation) {
            return \DB::table('mosquito_systems_type_salary')
                ->where('type_id', $this->type->id)
                ->where('additional_id', $installation->additional_id ?? $installation)
                ->orderByDesc('count')
                ->first();
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
         * @TODO сделать отдельную функцию которая проходит по всем additional и выставляет ПРАВИЛЬНО needInstallation
         *
         * @param $additional
         * @return bool
         */
        protected function additionalIsInstallation($additional): bool {
            if ($additional->name != 'Без монтажа' && $additional->group_name == 'Монтаж') {
                $this->needInstallation = true;
                $this->installation = $additional;
                return true;
            }

            if ($additional->name == 'Без монтажа') {
                $this->needInstallation = false;
                $this->installation = $additional;
                return false;
            }

            return false;
        }

        /**
         * @return Collection
         */
        public function getOptions(): Collection {
            return $this->options;
        }

        /**
         * @return bool
         */
        public function getNeedMeasuring(): bool {
            return $this->needMeasuring;
        }
    }
