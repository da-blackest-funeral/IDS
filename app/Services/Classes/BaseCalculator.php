<?php

    namespace App\Services\Classes;

    use App\Models\MosquitoSystems\Type;
    use App\Models\SystemVariables;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    abstract class BaseCalculator implements Calculator
    {
        /**
         * Instance of current Request
         *
         * @var Request
         */
        protected Request $request;

        /**
         * Price of current product
         *
         * @var float
         */
        protected float $price = 0.0;

        /**
         * All information about product that will be converted to json
         *
         * @var Collection
         */
        protected Collection $options;

        /**
         * Price for installation works
         *
         * @var float
         */
        protected float $installationPrice;

        /**
         * Salary for installer that does installation
         *
         * @var float
         */
        protected float $installersWage = 0.0;

        /**
         * Price of delivery
         *
         * @var float
         */
        protected float $deliveryPrice = 0.0;

        /**
         * Count of products in current request
         *
         * @var int
         */
        protected int $count = 0;

        /**
         * Price of measuring
         *
         * @var float
         */
        protected float $measuringPrice = 0.0;

        public function __construct(Request $request) {
            $this->request = $request;
            $this->options = new Collection();
            $this->count = (int) $request->get('count');
        }

        /**
         * Main method of all calculators
         *
         * @return void
         */
        public function calculate(): void {
            if ($this->hasSquare()) {
                $this->setSquareCoefficient();
            }

            if ($this->request->get('measuring')) {
                $this->setMeasuringPrice();
            }

            if ($this->request->get('delivery')) {
                $this->calculateDelivery();
            }
        }

        /**
         * Write price of product to json
         *
         * @param $price
         * @return void
         */
        protected function savePrice($price) {
            $this->options->push([
                'Цена изделия: ' => $price * $this->count,
            ]);
        }

        /**
         * Write salary of installers to json
         *
         * @return void
         */
        protected function saveInstallersWage() {
            $this->options->push([
               'Заработок монтажника: ' => $this->installersWage
            ]);
        }

        /**
         * Multiplying price of product by count of products
         *
         * @return void
         */
        protected function setPriceForCount(): void {
            $this->price *= $this->count;
        }

        /**
         * Writing info about delivery to json
         *
         * @param $additional
         * @return void
         */
        protected function saveDelivery($additional) {
            $this->options->push([
                'Цена доставки: ' => $this->deliveryPrice,
                'Из этого за доп. количество километров: ' => $additional,
            ]);
        }

        /**
         * Determines if that order needs delivery
         *
         * @return bool
         */
        protected function needDelivery() {
            return $this->request->has('delivery') && $this->request->get('delivery');
        }

        /**
         * Adding delivery price to all product's price
         *
         * @return void
         */
        protected function addDelivery() {
            $this->price += $this->deliveryPrice;
        }

        /**
         * Adding measuring price to all product's price
         *
         * @return void
         */
        protected function addMeasuringPrice() {
            $this->price += $this->measuringPrice;
        }

        /**
         * Calculates delivery price, salary for delivery and writes it
         * to json data
         *
         * @return void
         */
        protected function calculateDelivery() {
            if (!$this->needDelivery()) {
                return;
            }

            $distance = (float) $this->request->get('kilometres');

            $additionalDistancePrice = SystemVariables::where('name', 'additionalPriceDeliveryPerKm')
                ->first();

            $this->deliveryPrice += $additionalDistancePrice->value * $distance;

            $type = Type::byCategory($this->request->get('categories'));
            $this->deliveryPrice += $type->delivery;

            $additionalDistanceWage = SystemVariables::where('name', 'additionalWagePerKm')
                ->first();
            $this->installersWage += $additionalDistanceWage->value * $distance * $this->count;

            $this->saveDelivery($additionalDistancePrice->value * $distance);
        }

        /**
         * Determines if that calculator has HasSquare trait,
         * because some products hasn't square
         *
         * @return bool
         */
        protected function hasSquare(): bool {
            return in_array(HasSquare::class, class_uses($this));
        }

        /**
         * Calculates the measuring price and salary, writes info in json
         *
         * @return void
         */
        protected function setMeasuringPrice() {
            $measuring = SystemVariables::where('name', 'measuring')
                ->first(['value', 'description']);

            $this->saveSystemOptions($measuring);

            $measuringWage = SystemVariables::where('name', 'measuringWage')
                ->first(['value', 'description']);

            $this->saveSystemOptions($measuringWage);

            $this->measuringPrice += $measuring->value;
        }

        /**
         * Writes info of system variables
         *
         * @param SystemVariables $variable
         * @param $count
         * @return void
         */
        protected function saveSystemOptions(SystemVariables $variable, $count = 1) {
            $this->options->push([
                $variable->description => $variable->value * $count
            ]);
        }

        /**
         * @return float
         */
        public function getPrice(): float {
            return $this->price;
        }

        /**
         * @param float $price
         * @return void
         */
        public function setPrice(float $price): void {
            $this->price = $price;
        }

        /**
         * @return Collection
         */
        public function getOptions(): Collection {
            return $this->options;
        }

        /**
         * @return float
         */
        public function getDeliveryPrice(): float {
            return $this->deliveryPrice;
        }

        /**
         * @param Request $request
         * @return void
         */
        public function setRequest(Request $request): void {
            $this->request = $request;
        }

        /**
         * @return float
         */
        public function getInstallersWage(): float {
            return $this->installersWage;
        }
    }
