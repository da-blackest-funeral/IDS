<?php

    namespace App\Services\Classes;

    use App\Models\MosquitoSystems\Additional;
    use App\Models\MosquitoSystems\Product;
    use App\Models\MosquitoSystems\Type;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    class MosquitoSystemsCalculator extends BaseCalculator
    {
        use HasSquare;

        // todo: ремонт

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

        public function __construct(Request $request) {
            parent::__construct($request);

            try {
                $this->type = Type::byCategory($request->get('categories'));
            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
                return view('welcome')->withErrors([
                    'not_found' => 'Тип не найден',
                ]);
            }

            $this->additional = new Collection();
        }

        public function calculate(): void {
            /*
             * Making all preparations that are the same to all products
             */
            parent::calculate();

            /*
             * Firstly, need to calculate product's main price
             */
            $this->setProductPrice();

            /*
             * Then, calculating price of additional attributes
             */
            $this->setPriceForAdditional();

            /*
             * Multiplying prices of additional and main product's price by count of products.
             * Delivery price, salary, measuring price etc. don't depend on count.
             */
            $this->setPriceForCount();

            /*
             * Because delivery doesn't depend on count and other order characteristics,
             * it's price must be added here. It had already been calculated in BaseCalculator.
             */
            $this->addDelivery();

            /*
             * Similarly with the price of measurement
             */
            $this->addMeasuringPrice();

            /*
             * Calculating salary based on list of additional items
             */
            $this->calculateSalary();

            $this->saveInstallersWage();
        }

        /**
         * Product's main price (of one square meter) determines by the combination
         * of three parameters: tissue, type and profile.
         * It's model called Product.
         * That price multiplies by square - but if square <= 1, squareCoefficient = 1, else
         * squareCoefficient = square (in square meters)
         *
         * @return \Illuminate\Http\RedirectResponse|void
         */
        protected
        function setProductPrice() {
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
        protected function setPriceForAdditional() {
            $typeId = $this->type->id;

            $i = 1;
            while ($this->request->has("group-$i")) {
                // todo возможно заполнить коллекцию id-шников, а потом сделать whereIn()->with('group')
                try {
                    $additional = \DB::table('mosquito_systems_type_additional')
                        ->where('type_id', $typeId)
                        ->where('additional_id', $this->request->get("group-$i"))
                        ->first();

                    $additionalPrice = $additional->price;

                    if (!$this->additionalIsInstallation($additional)) {
                        $additionalPrice *= $this->squareCoefficient;
                    }

                    $this->saveAdditional($additional->additional_id, $additionalPrice);

                    $this->price += $additionalPrice ?? 0;

                    if ($this->additionalIsInstallation($additional)) {
                        $this->installationPrice = $additionalPrice;
                    }

                } catch (\Exception $exception) {
                    \Debugbar::alert($exception->getMessage());
                }
                $i++;
            }
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
            foreach ($this->additional as $item) {
                if (!$this->additionalIsInstallation($item)) {
                    continue;
                }

                $salary = \DB::table('mosquito_systems_type_salary')
                    ->where('type_id', $this->type->id)
                    ->where('additional_id', $item->id)
                    ->where('count', $this->count)
                    ->first();

                if ($salary) {
                    $this->installersWage += $salary->salary;
                } else {
                    $salary = \DB::table('mosquito_systems_type_salary')
                        ->where('type_id', $this->type->id)
                        ->where('additional_id', $item->id)
                        ->orderByDesc('count')
                        ->first();
                    $this->installersWage += $salary->salary + $this->count * $salary->salary_for_count;
                }
            }
        }

        /**
         * Saving info to json
         *
         * @param $additionalId
         * @param $price
         * @return void
         */
        protected function saveAdditional($additionalId, $price) {
            $additional = Additional::findOrFail($additionalId);

            $this->options->push([
                "Доп. $additional->name: " => $price * $this->count,
            ]);

            $this->additional->push($additional);
        }

        /**
         * Determines if additional belongs to installation group of services
         *
         * @param $additional
         * @return bool
         */
        protected function additionalIsInstallation($additional): bool {
            if (get_class($additional) != 'App\Models\MosquitoSystems\Additional') {
                return Additional::findOrFail($additional->additional_id ?? $additional->id)
                        ->group
                        ->name == 'Монтаж';
            } else {
                return $additional->group->name == 'Монтаж';
            }
        }

        /**
         * @return Collection
         */
        public function getOptions(): Collection {
            return $this->options;
        }
    }
