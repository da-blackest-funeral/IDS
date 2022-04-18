<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Helpers\MosquitoSystemsHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MosquitoSystemsController extends Controller
{
    /**
     * Returns profiles for mosquito systems
     *
//     * @return \Illuminate\Contracts\View\View
     */
    public function profile(): JsonResponse
    {
        $data = MosquitoSystemsHelper::profiles();
        return response()
            ->json(compact('data'));
    }

    public function bracing() {
        return '<div><p class="h3">Пока не готово :-)</p></div>';
        // todo функционал кнопки добавить крепление будет полноценным когда я перенесу таблицу услуг
    }

    /**
     * Returns additional fields
     *
     * @param Request $request
//     * @return \Illuminate\Contracts\View\View
     */
    public function additional(Request $request): JsonResponse
    {
        // для москитных сеток в реквесте должны быть category_id, tissue_id, profile_id
        // а для стеклопакетов количество камер
        // в остальных первые 3 селекта не влияют на вывод дополнительных полей

        return response()
            ->json(MosquitoSystemsHelper::additional());
    }
}
