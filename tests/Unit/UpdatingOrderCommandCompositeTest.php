<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Salaries\InstallerSalary;
use App\Services\Commands\Classes\RemoveDeliveryCommand;
use App\Services\Visitors\Classes\UpdateOrderCommandComposite;
use App\Services\Visitors\Classes\UpdateOrderDto;
use Tests\CreatesApplication;
use Tests\Feature\Orders\MosquitoSystems\TestHelper;
use Tests\TestCase;

class UpdatingOrderCommandCompositeTest extends TestCase
{
    use CreatesApplication;

    public function setUpDefaultActions() {
        \Artisan::call('migrate:fresh');
        \Artisan::call('db:seed');
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->setUpDefaultActions();

        $helper = new TestHelper();
        /** @var Order $order */
        $order = Order::factory()->create();

        $helper->createDefaultSalary();

        $salary = InstallerSalary::first();
        $price = $order->price;
        $visits = $order->additional_visits;
        $deliveryPrice = (1 + $visits) * $helper->defaultDeliverySum();

        $dto = new UpdateOrderDto([
            'delivery' => true,
            'measuring-price' => systemVariable('measuring')
        ]);
        $composite = new UpdateOrderCommandComposite($dto, $order, $salary);
        $composite->addCommand(new RemoveDeliveryCommand($order))
            ->execute();

        self::assertTrue($composite->result());
        self::assertTrue(
            $order->price == $price -= $deliveryPrice
        );
    }
}
