<?php

namespace App\Services\Classes;

use App\Models\MosquitoSystems\Product;
use App\Models\MosquitoSystems\Type;
use Illuminate\Support\Collection;

class MosquitoSystemsCalculator extends BaseCalculator
{
    use HasSquare;

    /*
     * todo: ремонт, монтаж, демонтаж, доставка, зп монтажникам
     * вроде готово, протестировать todo: просчет цены за все additional (которые в group)
     */

    protected Collection $options;
    protected float $installationPrice;
    protected float $deliveryPrice;

    public function calculate(): void {
        parent::calculate();
        $this->getProductPrice();
        $this->setPriceForAdditional();
        $this->setPriceForCount();
        $this->setDeliveryPrice();
    }

    protected function getProductPrice() {
        try {
            $this->price = Product::where('tissue_id', $this->request->get('tissues'))
                ->where('profile_id', $this->request->get('profiles'))
                ->where('category_id', $this->request->get('categories'))
                ->firstOrFail()
                ->price;
        } catch (\Exception $exception) {
            \Debugbar::alert($exception->getMessage());
            return view('welcome')->withErrors([
                'not_found' => 'Такого товара не найдено',
            ]);
        }
    }

    protected function setPriceForAdditional() {
        try {
            $typeId = Type::where('category_id', $this->request->get('categories'))
                ->firstOrFail()
                ->id;
        } catch (\Exception $exception) {
            \Debugbar::alert($exception->getMessage());
            return view('welcome')->withErrors([
                'not_found' => 'Товар не найден',
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
                if ($this->additionalIsInstallation($additional)) {
                    $this->installationPrice = $additionalPrice;
                }

            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
            }
            $this->price += $additionalPrice ?? 0;
            $i++;
        }
    }

    protected function additionalIsInstallation($additional): bool {
        // todo начнет работать после переноса данных по москитным системам
        return $additional->group()
                ->name == 'installation';
    }

    protected function setDeliveryPrice() {
        $this->deliveryPrice = Type::where(
            'category_id',
            $this->request->get('categories')
        )
            ->first()
            ->delivery;
    }

    /**
     * @return float
     */
    public function getDeliveryPrice(): float {
        return $this->deliveryPrice;
    }
}
