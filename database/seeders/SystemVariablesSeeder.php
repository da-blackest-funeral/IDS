<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SystemVariablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $variables = [
            [
                'value' => 4296,
                'name' => 'oneCamGlazedWindow',
                'description' => 'Цена за 1 кв.м. обычного однокамерного стеклопакета',
            ],
            [
                'value' => 5400,
                'name' => 'twoCamGlazedWindow',
                'description' => 'Цена за 1 кв.м. обычного двухкамерного стеклопакета',
            ],
            [
                'value' => 600,
                'name' => 'measuring',
                'description' => 'Стоимость замера',
            ],
            [
                'value' => 480,
                'name' => 'measuringWage',
                'description' => 'Зарплата монтажнику за замер',
            ],
            [
                'value' => 480,
                'name' => 'delivery',
                'description' => 'заработок за доставку (без монтажа или если нет сеток, но есть аксессуары)',
            ],
            [
                'value' => 240,
                'name' => 'measuringWhenCancel',
                'description' => 'заработок монтажника за замер, если заказ отменен',
            ],
            [
                'value' => 5000,
                'name' => 'minSumOrder',
                'description' => 'Минимальная сумма заказа',
            ],
            [
                'value' => 48,
                'name' => 'additionalPriceDeliveryPerKm',
                'description' => 'Стоимость доставки за каждый км за кад для клиента',
            ],
            [
                'value' => 12,
                'name' => 'additionalWagePerKm',
                'description' => 'ЗП монтажника и доставщика за каждый км за кад',
            ],
            [
                'value' => 0.3,
                'name' => 'repairCoefficient',
                'description' => 'Процентное соотношение цены ремонта сеток к цене самих сеток',
            ],
            [
                'value' => 24,
                'name' => 'priceInstallingPlug',
                'description' => 'Стоимость монтажа одной заглушки подоконника',
            ],
            [
                'value' => 72,
                'name' => 'pricePlug',
                'description' => 'Стоимость одной заглушки подоконника',
            ],
            [
                'value' => 600,
                'name' => 'uninstallWindowsill',
                'description' => 'Демонтаж одного подоконника',
            ],
            [
                'value' => 480,
                'name' => 'additionalWageForWish',
                'description' => 'Прибавка к ЗП за добавленное пожелание клиентов',
            ],
            [
                'value' => 4947,
                'name' => 'priceGlazedWindowWithHeatingOneCam',
                'description' => 'Цена за 1 м.кв. однокамерного теплопакета',
            ],
            [
                'value' => 5914,
                'name' => 'priceGlazedWindowWithHeatingTwoCam',
                'description' => 'Цена за 1 м.кв. двухкамерного теплопакета',
            ],
            [
                'value' => 1680,
                'name' => 'priceAdditionalDelivery',
                'description' => 'Стоимость прибавки за доставку на юг для Андрею Доп. Север',
            ],
            [
                'value' => 1.5,
                'name' => 'coefficientFastCreating',
                'description' => 'Коэф. срочного изготовления стеклопакета. (влияет на : стеклопакет, стекла, теплопакет, замер, доставку, монтаж стеклоп., километраж)',
            ],
            [
                'value' => 1.3,
                'name' => 'coefficientMosquitoSystemFast',
                'description' => 'Коэф. срочного изготовления сеток (кроме Ral)',
            ],
            [
                'value' => 1.06,
                'name' => 'profileLossFactor',
                'description' => 'Коэффициент потерь профиля',
            ],
            [
                'value' => 1.3,
                'name' => 'tissueLossFactor',
                'description' => 'Коэффициент потерь сетки',
            ],
            [
                'value' => 500,
                'name' => 'fee',
                'description' => 'Сумма штрафа для монтажников если вовремя не переведены деньги',
            ],
            [
                'value' => 1.06,
                'name' => 'impostLossFactor',
                'description' => 'Коэффициент потерь по импосту',
            ],
            [
                'value' => 1.01,
                'name' => 'cordLossFactor',
                'description' => 'Коэффициент потерь по шнуру',
            ],
            [
                'value' => 1.15,
                'name' => 'feltLossFactor',
                'description' => 'Коэффициент потерь по фетру',
            ],
            [
                'value' => 1.06,
                'name' => 'runnersLossFactor',
                'description' => 'Коэффициент потерь по полозьям',
            ],
            [
                'value' => 1.15,
                'name' => 'sealerSlidingGridsLossFactor',
                'description' => 'Коэффициент потерь по уплотнителю для раздвижных сеток',
            ],
            [
                'value' => 600,
                'name' => 'priceDeliverySlope',
                'description' => 'Цена доставки откосов',
            ],
            [
                'value' => 342,
                'name' => 'priceUninstallSlope',
                'description' => 'Цена демонтажа откосов',
            ],
            [
                'value' => 100,
                'name' => 'dollarExchangeRate',
                'description' => 'Курс доллара по ЦБ',
            ],
            [
                'value' => 3,
                'name' => 'countDaysProduction',
                'description' => 'Количество дней про-ва сеток у себя (пн-сб - рабочие дни)',
            ],
            [
                'value' => 18000,
                'name' => 'priceGlazedWindowsWithHoleOneCam',
                'description' => 'Цена за 1 кв.м. однокамерного стеклопакета с отверстием',
            ],
            [
                'value' => 20400,
                'name' => 'priceGlazedWindowsWithHoleTwoCam',
                'description' => 'Цена за 1 кв.м. двухкамерного стеклопакета с отверстием',
            ],
            [
                'value' => 600,
                'name' => 'priceDeliveryOverlay',
                'description' => 'Стоимость доставки накладки для подоконников',
            ],
            [
                'value' => 1,
                'name' => 'overlayLossCoefficient',
                'description' => 'Коэффициент потерь при списании накладки на подоконник',
            ],
            [
                'value' => 1.2,
                'name' => 'rivetLossCoefficient',
                'description' => 'Коэффициент потерь по заклепкам',
            ],
            [
                'value' => 7200,
                'name' => 'priceDeliveryGlazedWindowByGazel',
                'description' => 'Стоимость доставки стеклопакета площадью больше 1.33 м.кв.',
            ],
            [
                'value' => 3000,
                'name' => 'priceIfNeedLoader',
                'description' => 'Стоимость грузчика (дополнительный сотрудник при монтаже стеклопакета)',
            ],
            [
                'value' => 1.03,
                'name' => 'wrapLossFactor',
                'description' => 'Коэффициент потерь по пленкам у монтажников',
            ],
            [
                'value' => 2,
                'name' => 'shtapikAdditionalPrice',
                'description' => 'Коэффициент на сколько умножить себестоимость штапиков',
            ],
            [
                'value' => 1.2,
                'name' => 'coefficientSaleClippings',
                'description' => 'Скидка на пленку при покупке обрезков',
            ],
            [
                'value' => 50,
                'name' => 'petrolPrice',
                'description' => 'Цена АИ-95',
            ],
            [
                'value' => 2,
                'name' => 'priceCallOneOrder',
                'description' => 'Стоимость за общение по телефону, за посещение 1 точки',
            ],
            [
                'value' => 20,
                'name' => 'countMinutesRephoneMail',
                'description' => 'Кол-во минут на звонок (перезвон) по заявке на почту',
            ],
            [
                'value' => 7183,
                'name' => 'plisseRussiaPrice',
                'description' => 'Сетки плиссе Россия (отображение на странице сайта)',
            ],
            [
                'value' => 9,
                'name' => 'fuelConsumptionFor100Km',
                'description' => 'Расход бензина на 100 км для сотрудников с машиной (монтажник, замерщик, курьер)',
            ],
            [
                'value' => 7,
                'name' => 'fuelConsumptionFor100KmWithoutCar',
                'description' => 'Расход бензина на 100 км для сотрудников без машины',
            ],
            [
                'value' => 0,
                'name' => 'additionalWageForVSNDelivery',
                'description' => 'Прибавка к ЗП за установку сеток VSN для курьера',
            ],
            [
                'value' => 600,
                'name' => 'sumAdditionalVisit',
                'description' => 'Стоимость для клиента за 1 дополнительный выезд',
            ],
            [
                'value' => 180,
                'name' => 'additionalWageAntidustMeasure',
                'description' => 'Прибавка к ЗП замерщика за продажу москитной сетки антипыль вместо обычной',
            ],
            [
                'value' => 240,
                'name' => 'additionalWageRespilonMeasure',
                'description' => 'Прибавка к ЗП замерщика за продажу москитной сетки Respilon вместо обычной',
            ],
            [
                'value' => 3000,
                'name' => 'priceDeliveryTaxi',
                'description' => 'Стоимость доставки сеток, которые не влезают в багажник (у замерщикая другая логика, там используется газель)',
            ],
            [
                'value' => 250,
                'name' => 'referalBonus',
                'description' => 'Бонус клиенту за приведенного клиента',
            ],
            [
                'value' => 1.24,
                'name' => 'coefficientCompaniesCar',
                'description' => 'На сколько процентов меньше ЗП монт. если авто. компании',
            ],
            [
                'value' => 2400,
                'name' => 'connectorPrice',
                'description' => 'Цена за коннектор (при мощности больше 3500 ВТ) у стеклопакета с подогревом',
            ],
            [
                'value' => 360,
                'name' => 'additionalWageForTakeaway',
                'description' => 'Прибавка за каждые 0.2 м.кв за вынос старого стеклопакета',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow5',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 5',
            ],
            [
                'value' => 4800,
                'name' => 'priceTakeawayGlazedWindow10',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 10',
            ],
            [
                'value' => 4800,
                'name' => 'priceTakeawayGlazedWindow15',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 15',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow20',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 20',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow25',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 25',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow30',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 30',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow35',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 35',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow40',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 40',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow45',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 45',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow50',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 50',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow55',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 55',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow60',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 60',
            ],
            [
                'value' => 3600,
                'name' => 'priceTakeawayGlazedWindow65',
                'description' => 'Цена за вынос стеклопакета, если кол-во <= 65',
            ],
            [
                'value' => 480,
                'name' => 'priceUninstallWrap',
                'description' => 'Демонтаж пленки',
            ],
        ];

        foreach ($variables as $variable) {
            \DB::table('system_variables')->insert($variable);
        }
    }
}
