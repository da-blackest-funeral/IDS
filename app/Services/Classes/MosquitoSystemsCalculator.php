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
     * todo: для сеток пр-ва италия другая логика расчета (сделать отдельный класс калькулятор)
     */

    protected Collection $options;

    public function calculate(): void {
        $this->getProductPrice();
        $this->setPriceForAdditional();
        $this->setPriceForCount();
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
            return view('welcome')->withErrors([
                'not_found' => 'Товар не найден',
            ]);
        }

        $i = 1;
        while ($this->request->has("group-$i")) {
            try {
                $additionalPrice = \DB::table('mosquito_systems_type_additional')
                    ->where('type_id', $typeId)
                    ->where('additional_id', $this->request->get("group-$i"))
                    ->first()
                    ->price;
            } catch (\Exception $exception) {
                \Debugbar::alert($exception->getMessage());
            }
            $this->price += $additionalPrice ?? 0;
            $i++;
        }
    }
}
