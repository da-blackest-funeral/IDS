<?php

    namespace App\Services\Classes;

    use App\Models\GlazedWindows\Additional;
    use App\Models\GlazedWindows\GlazedWindows;
    use App\Models\SystemVariables;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;

    class GlazedWindowsCalculator extends BaseCalculator
    {
        use HasSquare;

        protected Collection $additional;

        public function __construct(Request $request) {
            parent::__construct($request);
            $this->additional = Additional::groupBy('group')
                ->get(['group']);
        }

        public function calculate(): void {
            parent::calculate();
            $this->setPriceFromCameras();
            $this->calculateGlazedWindowsPrice();
            $this->setAdditionalPrice();
            $this->setPriceForCount();
            $this->additionalPriceForSquare();
            // все что выше работает правильно, сравнил с настоящей IDS
        }

        protected function setPriceFromCameras() {
            if ($this->request->get('cameras-count') == 1) {
                $oneCameraGlazedWindow = SystemVariables::oneCameraPrice();
                $this->price += $oneCameraGlazedWindow->value * $this->squareCoefficient;

                $this->saveMainPrice($oneCameraGlazedWindow);
            } elseif ($this->request->get('cameras-count') == 2) {
                $this->price += SystemVariables::twoCameraPrice()->value * $this->squareCoefficient;
            }
        }

        protected function saveMainPrice($glazedWindow) {
            $this->options->push([
                $glazedWindow->description => $glazedWindow->value,
                'Общая цена: ' => $glazedWindow->value * $this->squareCoefficient,
            ]);
        }

        protected function setAdditionalPrice() {
            foreach ($this->additional as $field) {
                // todo заменить на while(request has $field->group . '-' . $i)
                // todo придумать как избавиться от n+1 query
                for ($i = 1; $i <= 3; $i++) {
                    $selectName = $field->group . '-' . $i;
                    if ($this->request->has($selectName)) {

                        $this->saveAdditional(
                            $field,
                            $this->request->get($selectName),
                            $i
                        );

                        $this->price += $this->request->get($selectName) * $this->squareCoefficient;
                    }
                }
            }
        }

        protected function saveAdditional($additional, $price, $number) {

            $additional = Additional::with('layer')
                ->where('group', $additional->group)
                ->where('price', $price)
                ->get(['option_name', 'layer_id'])
                ->firstOrFail();

            $this->options->push([
                "$additional->option_name - $number {$additional->layer->name}: " => $price * $this->squareCoefficient,
            ]);
        }

        protected function additionalPriceForSquare() {
            if ($this->squareCoefficient > 2) {
                $this->price *= 1.2;
            }

            if ($this->squareCoefficient > 4) {
                $this->price *= 1.25;
            }
        }

        protected function getIds(): array {
            $result = [];
            for ($i = 1; $i <= 3; $i++) {
                if ($this->request->has("glass-width-$i")) {
                    $result[] = $this->request->get("glass-width-$i");
                }

                if ($this->request->has("cameras-width-$i")) {
                    $result[] = $this->request->get("cameras-width-$i");
                }
            }

            return $result;
        }

        protected function calculateGlazedWindowsPrice() {
            foreach ($this->getIds() as $id) {
                $this->price += GlazedWindows::find($id, ['price'])->price;
            }
        }

        public function getOptions(): Collection {
            return $this->options;
        }
    }
