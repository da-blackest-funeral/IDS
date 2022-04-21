<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Helpers\MosquitoSystemsHelper;
use Illuminate\Contracts\View\View;

class MosquitoSystemsController extends Controller
{
    /**
     * Returns profiles for mosquito systems
     *
     * @return View
     */
    public function profile(): View
    {
        $label = 'Профиль';
        $data = MosquitoSystemsHelper::profiles();
        return view('ajax.mosquito-systems.profiles')->with(compact('data', 'label'));
    }

    public function bracing() {
        return '<div><p class="h3">Пока не готово :-)</p></div>';
        // todo функционал кнопки добавить крепление будет полноценным когда я перенесу таблицу услуг
    }

    /**
     * Returns additional fields
     *
     * @return View
     */
    public function additional(): View
    {
        // для москитных сеток в реквесте должны быть category_id, tissue_id, profile_id
        // а для стеклопакетов количество камер
        // в остальных первые 3 селекта не влияют на вывод дополнительных полей

        return view('ajax.mosquito-systems.additional')
            ->with(MosquitoSystemsHelper::additional());
    }
}
