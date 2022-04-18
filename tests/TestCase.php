<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Feature\Orders\MosquitoSystems\TestHelper;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUpDefaultActions() {
        $this->seed();
        $this->actingAs(User::first());
        $this->withoutExceptionHandling();
    }

    protected function setUp(): void {
        parent::setUp();

        $this->testHelper = new TestHelper();
    }
}
