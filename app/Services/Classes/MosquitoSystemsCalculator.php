<?php

namespace App\Services\Classes;

use App\Models\MosquitoSystems\Additional;
use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MosquitoSystemsCalculator extends BaseCalculator
{
    use HasSquare;

    /*
     * todo: ремонт, монтаж, демонтаж, доставка, зп монтажникам
     */

    protected Collection $options;
    protected float $installationPrice;
    protected float $deliveryPrice;

    public function calculate(): void {
        parent::calculate();
        $this->setProductPrice();
        $this->setPriceForAdditional();
//        $this->setPriceForCount();
        $this->setDeliveryPrice();
    }

    protected function setProductPrice() {
        try {
            $this->price = Product::where('tissue_id', $this->request->get('tissues'))
                ->where('profile_id', $this->request->get('profiles'))
                ->whereHas('type', function (Builder $query) {
                    $query->where('category_id', $this->request->get('categories'));
                })
                ->firstOrFail()
                ->price;

            $this->savePrice();

        } catch (\Exception $exception) {
            \Debugbar::alert($exception->getMessage());
            return view('welcome')->withErrors([
                'not_found' => 'Такого товара не найдено',
            ]);
        }
    }

    protected function savePrice() {
        $this->options->push([
            'Цена изделия: ' => $this->price,
        ]);
    }

    protected function saveAdditional($additionalId, $price) {
        $name = Additional::findOrFail($additionalId)->name;
        $this->options->push([
           "Доп. $name: " => $price
        ]);
    }

    protected function setPriceForAdditional() {
        try {
            $typeId = Type::where('category_id', $this->request->get('categories'))
                ->firstOrFail()
                ->id;
        } catch (\Exception $exception) {
            \Debugbar::alert($exception->getMessage());
            return view('welcome')->withErrors([
                'not_found' => 'Тип не найден',
            ]);
        }

        $i = 1;
        while ($this->request->has("group-$i")) {
            try {
                $additional = \DB::table('mosquito_systems_type_additional')
                    ->where('type_id', $typeId)
                    ->where('additional_id', $this->request->get("group-$i"))
                    ->first();

                $additionalPrice = $additional->price * $this->squareCoefficient;

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
        return Additional::findOrFail($additional->additional_id)
                ->group()
                ->name == 'Монтаж';
    }

    /**
     * @return Collection
     */
    public function getOptions(): Collection {
        return $this->options;
    }

    protected function saveDeliveryPrice() {
        $this->options->push([
           'Цена доставки: ' => $this->deliveryPrice
        ]);
    }

    protected function setDeliveryPrice() {

        if (!$this->needDelivery()) {
            return;
        }

        $this->deliveryPrice = Type::where('category_id', $this->request->get('categories'))
            ->first()
            ->delivery;

        $this->saveDeliveryPrice();

        $this->price += $this->deliveryPrice;
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
}
