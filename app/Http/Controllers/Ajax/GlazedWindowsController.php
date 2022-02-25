<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\GlazedWindows\Glass;
use App\Models\GlazedWindows\GlazedWindows;
use App\Models\GlazedWindows\TemperatureController;
use App\Models\GlazedWindows\WithHeating;
use Illuminate\Http\Request;

class GlazedWindowsController extends Controller
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * Defines, how to display last field to user
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getLast() {
        $data = [];
        $name = 'cameras-count';
        $label = 'Кол-во камер';
//        $isWithHeating = false;
        if ($this->isWithHeating()) {
            $data = WithHeating::all(['id', 'name']);
//            $isWithHeating = true;
            $name = 'with-heating';
            $label = 'Тип стеклопакета';
        } elseif ($this->isGlass()) {
            $data = Glass::query()
                ->select('thickness')
                ->groupBy('thickness')
                ->get();
        }
        \Debugbar::info($data);
        return view('ajax.glazed-windows.last')
            ->with(compact('data', 'name', 'label'));
    }

    /**
     * Defines, how to display additional fields to user
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function additional() {
        if ($this->isWithHeating()) {
            return $this->withHeating();
        } elseif ($this->isGlass()) {
            return $this->glass();
        }

        return $this->glazedWindows();
    }

    /**
     * Checks, if this request made for glazed windows with heating
     *
     * @return bool
     */
    protected function isWithHeating(): bool {
        return (int)$this->request->get('categoryId') == 17;
    }

    /**
     * @return bool
     */
    protected function isGlass(): bool {
        return (int)$this->request->get('categoryId') == 18;
    }

    /**
     * Returns view with data for glazed windows with heating
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function withHeating() {
        $camerasCount = WithHeating::with('group')
            ->find((int)$this->request->get('additional'))
            ->first()
            ->cameras;
        $widthArray = \DB::table('glazed_windows_with_heating_width')->get();
        $temperatureControllers = TemperatureController::all();

        \Debugbar::info(compact('camerasCount', 'widthArray',
            'temperatureControllers'
        ));
        return view('ajax.glazed-windows.with-heating-additional')
            ->with(
                compact(
                    'camerasCount',
                    'widthArray',
                    'temperatureControllers'
                )
            );

    }

    /**
     * Returns view for glass
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function glass() {
        return view('ajax.glazed-windows.glass-additional');
    }

    /**
     * Returns view with data for other categories of glazed windows
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function glazedWindows() {
        $camerasCount = (int)$this->request->get('additional');
        $additionalForCameras = $this->getAdditionalSelects('Камера');
        $additionalForGlass = $this->getAdditionalSelects('Стекло');
        \Debugbar::info(compact('additionalForGlass', 'additionalForCameras'));

        $glassWidth = GlazedWindows::select(['id', 'name'])
            ->where('layer_id', 2)
            ->get();
        \Debugbar::info($glassWidth);

        $camerasWidth = GlazedWindows::with('camerasWidth')
            ->where('category_id', (int)$this->request->get('categoryId'))
            ->where('layer_id', 1)
            ->get()
            ->pluck('camerasWidth');

        return view('ajax.glazed-windows.additional')
            ->with(
                compact(
                    'camerasWidth',
                    'camerasCount',
                    'glassWidth',
                    'additionalForCameras',
                    'additionalForGlass',
                )
            );
        /*
         * '' => $this->getAdditionalSelects('Камера'),
                'additionalForGlass' => $this->getAdditionalSelects('Стекло'),
         */
    }

    protected function getAdditionalSelects(string $layer) {
//        return
        $options = \DB::table('glazed_windows_additional')
            ->select(['id', 'name', 'value', 'layer_id', 'price'])
            ->where(
                'layer_id',
                \DB::table('glazed_windows_layers')
                    ->select('id')
                    ->where('name', 'like', $layer)
                    ->first()
                    ->id
            )->get();

        $selects = \DB::table('glazed_windows_additional')
            ->select('name')
            ->where(
                'layer_id',
                \DB::table('glazed_windows_layers')
                    ->select('id')
                    ->where('name', 'like', $layer)
                    ->first()
                    ->id
            )
            ->groupBy('name')
            ->get();

        return compact('options', 'selects');
    }
}
