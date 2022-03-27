<?php

    namespace App\Services\Classes;

    use App\Models\MosquitoSystems\Type;
    use App\Models\SystemVariables;
    use App\Services\Interfaces\Calculator;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use JetBrains\PhpStorm\Pure;

    abstract class BaseCalculator implements Calculator
    {
        protected Request $request;
        protected float $price = 0.0;
        protected Collection $options;
        protected float $installationPrice;
        protected float $installersWage = 0.0;
        protected float $deliveryPrice = 0.0;
        protected int $count = 0;
        protected float $measuringPrice = 0.0;

        public function __construct(Request $request) {
            $this->request = $request;
            $this->options = new Collection();
            $this->count = (int) $request->get('count');
        }

        protected function savePrice($price) {
            $this->options->push([
                'Цена изделия: ' => $price * $this->count,
            ]);
        }

        protected function saveInstallersWage() {
            $this->options->push([
               'Заработок монтажника: ' => $this->installersWage
            ]);
        }

        /**
         * @return float
         */
        public function getInstallersWage(): float {
            return $this->installersWage;
        }

        protected function setPriceForCount(): void {
            $this->price *= $this->count;
        }

        public function setRequest(Request $request): void {
            $this->request = $request;
        }

        protected function saveDelivery($additional) {
            $this->options->push([
                'Цена доставки: ' => $this->deliveryPrice,
                'Из этого за доп. количество километров: ' => $additional,
            ]);
        }

        protected function needDelivery() {
            return $this->request->has('delivery') && $this->request->get('delivery');
        }

        /**
         * @return float
         */
        public function getDeliveryPrice(): float {
            return $this->deliveryPrice;
        }

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

        protected function addDelivery() {
            $this->price += $this->deliveryPrice;
        }

        protected function addMeasuringPrice() {
            $this->price += $this->measuringPrice;
        }

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

        protected function hasSquare(): bool {
            return in_array(HasSquare::class, class_uses($this));
        }

        protected function setMeasuringPrice() {
            $measuring = SystemVariables::where('name', 'measuring')
                ->first(['value', 'description']);

            $this->saveSystemOptions($measuring);

            $measuringWage = SystemVariables::where('name', 'measuringWage')
                ->first(['value', 'description']);

            $this->saveSystemOptions($measuringWage);

            $this->measuringPrice += $measuring->value;
        }

        protected function saveSystemOptions(SystemVariables $variable, $count = 1) {
            $this->options->push([
                $variable->description => $variable->value * $count
            ]);
        }

        public function getPrice(): float {
            return $this->price;
        }

        public function setPrice(float $price): void {
            $this->price = $price;
        }

        public function getOptions(): Collection {
            return $this->options;
        }
    }
