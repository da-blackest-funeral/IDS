<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\Salaries\InstallerSalary;
use App\Services\Commands\Classes\RemoveDeliveryCommand;
use App\Services\Helpers\Config\SalaryTypesEnum;
use App\Services\Visitors\Classes\UpdateOrderCommandComposite;
use App\Services\Visitors\Classes\UpdateOrderDto;
use Tests\CreatesApplication;
use Tests\TestCase;

class UpdatingOrderCommandCompositeTest extends TestCase
{
    use CreatesApplication;

    private Order $order;

    private InstallerSalary $salary;

    public function setUpDefaultActions() {
        \Artisan::call('migrate:fresh');
        \Artisan::call('db:seed');

        $this->order = Order::factory()->create();

        $this->testHelper->createDefaultSalary(type: SalaryTypesEnum::NO_INSTALLATION->value);

        $this->salary = InstallerSalary::first();
    }

    /**
     * @test
     * @return void
     */
    public function remove_delivery_command_test()
    {
        $this->setUpDefaultActions();

        $price = $this->order->price;
        $salary = $this->salary->sum;
        $visits = $this->order->additional_visits;
        $deliveryPrice = (1 + $visits) * $this->testHelper->defaultDeliverySum();

        $dto = new UpdateOrderDto([
            'delivery' => true,
            'measuring-price' => systemVariable('measuring')
        ]);
        $composite = new UpdateOrderCommandComposite($dto, $this->order, $this->salary);
        $composite->addCommand(new RemoveDeliveryCommand($this->order, $this->salary))
            ->execute();

        self::assertTrue($composite->result());
        self::assertTrue(
            $this->order->price == $price -= $deliveryPrice
        );

        self::assertTrue(
            $this->salary->sum == $salary - systemVariable('delivery')
        );
    }
}
