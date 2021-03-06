<?php

    namespace App\Services\Calculator\Classes;

    use App\Models\SystemVariables;
    use App\Services\Calculator\Interfaces\Calculator;
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
        protected float $installationPrice = 0;

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

        /**
         * @var bool
         */
        protected bool $needInstallation = false;

        protected mixed $installation;

        protected int $categoryId;

        public function __construct(Request $request) {
            $this->request = $request;
            $this->options = new Collection();
            $this->count = (int)$request->get('count');
            $this->categoryId = (int)$request->get('categories');
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

            $this->setSize();

            $this->setMeasuringPrice();

            $this->calculateDelivery();

            $this->setCategory();
        }

        protected function setCategory() {
            $this->options->put('category', $this->categoryId);
        }

        protected function setSize() {
            $this->options->put('size', [
                'height' => $this->request->get('height') ?? '??????',
                'width' => $this->request->get('width') ?? '??????',
            ]);
        }

        /**
         * @return float
         */
        public function getMeasuringPrice(): float {
            return $this->measuringPrice;
        }

        public function getCount(): int {
            return $this->count;
        }

        /**
         * @return float
         */
        public function getInstallationPrice(): float {
            return $this->installationPrice;
        }

        /**
         * @return mixed
         */
        public function getInstallation($property = null) {
            return $this->installation->$property ?? $this->installation;
        }

        /**
         * @return int
         */
        public function getCategoryId(): int {
            return $this->categoryId;
        }

        /**
         * @return bool
         */
        public function productNeedInstallation(): bool {
            return $this->needInstallation;
        }

        /**
         * Write price of product to json
         *
         * @param $price
         * @return void
         */
        protected function savePrice($price) {
            $this->options->put(
                'main_price',
                $price * $this->count,
            );
        }

        /**
         * Write salary of installers to json
         *
         * @return void
         */
        protected function saveInstallationData() {
            $this->options->put(
                'measuring', $this->measuringPrice ? : '??????????????????'
            );
        }

        /**
         * Multiplying price of product by count of products
         *
         * @return BaseCalculator
         */
        protected function setPriceForCount(): BaseCalculator {
            $this->price *= $this->count;
            return $this;
        }

        /**
         * Writing info about delivery to json
         *
         * @param $additional
         * @param $salary
         * @return void
         */
        protected function saveDelivery($additional, $salary) {
            $this->options->put(
                'delivery', [
                    'deliveryPrice' => $this->deliveryPrice,
                    'additional' => $additional,
                    'additionalSalary' => $salary > 0 ? $salary : '??????',
                ]
            );
        }

        /**
         * Determines if that order needs delivery
         *
         * @return bool
         */
        public function needDelivery(): bool {
            // as default, when creating orders, delivery are set to true
            if (!$this->request->has('delivery')) {
                return true;
            }

            return $this->request->get('delivery');
        }

        /**
         * Adding delivery price to all product's price
         *
         * @return BaseCalculator
         */
        protected function addDelivery(): BaseCalculator {
            if (! requestHasOrder() || order()->need_delivery) {
                $this->price += $this->deliveryPrice;
            }

            return $this;
        }

        /**
         * Adding measuring price to all product's price
         *
         * @return BaseCalculator
         */
        protected function addMeasuringPrice(): BaseCalculator {
            $this->price += $this->measuringPrice;

            return $this;
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
         * Writes info of system variables
         *
         * @param SystemVariables $variable
         * @param int $count
         * @return void
         */
        protected function saveSystemOptions(SystemVariables $variable, int $count = 1): void {
            $this->options->put(
                $variable->name,
                $variable->description . ' ' . $variable->value * $count > 0 ? $variable->value * $count : '??????????????????',
            );
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

        /**
         * Calculates the measuring price and salary, writes info in json
         *
         * @return void
         */
        abstract protected function setMeasuringPrice(): void;

        /**
         * Calculates delivery price, salary for delivery and writes it
         * to json data
         *
         * @return void
         */
        abstract protected function calculateDelivery(): void;
    }
