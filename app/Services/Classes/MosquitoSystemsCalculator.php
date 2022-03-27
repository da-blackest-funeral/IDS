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

        /*
         * todo: ремонт
         */

        protected Type $type;
        protected Collection $additional;

        public function __construct(Request $request) {
            parent::__construct($request);
            $this->type = Type::where('category_id', $this->request->get('categories'))
                ->firstOrFail();
            $this->additional = new Collection();
        }

        public function calculate(): void {
            parent::calculate();
            $this->setProductPrice();
            $this->setPriceForAdditional();
            $this->setPriceForCount();
            $this->addDelivery();
            $this->addMeasuringPrice();
            $this->calculateSalary();
            $this->saveInstallersWage();
        }

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
        }

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

        protected function saveAdditional($additionalId, $price) {
            $additional = Additional::findOrFail($additionalId);

            $this->options->push([
                "Доп. $additional->name: " => $price * $this->count,
            ]);

            $this->additional->push($additional);
        }

        protected function setPriceForAdditional() {
            try {
                $typeId = $this->type->id;
            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
                return view('welcome')->withErrors([
                    'not_found' => 'Тип не найден',
                ]);
            }

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

        protected function additionalIsInstallation($additional): bool {
            if (get_class($additional) != 'App\Models\MosquitoSystems\Additional') {
                return Additional::findOrFail($additional->additional_id ?? $additional->id)
                        ->group()
                        ->first()
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
