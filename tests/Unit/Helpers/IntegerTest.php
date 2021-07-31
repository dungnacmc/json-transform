<?php

namespace Tests\Unit\Helpers;

use App\Services\Helpers\Integer;
use Tests\TestCase;

class IntegerTest extends TestCase
{

    /**
     *
     * @return void
     */
    protected function setUp():void
    {
        parent::setUp();
    }

    /**
     * Test is positive integer method
     *
     * @return void
     */
    public function testIsPositive()
    {
        $this->assertTrue(Integer::isPositive(1));
        $this->assertFalse(Integer::isPositive(0));
        $this->assertFalse(Integer::isPositive(-1));
    }

    /**
     * Test is not negative integer method
     *
     * @return void
     */
    public function testIsNotNegative()
    {
        $this->assertTrue(Integer::isNotNegative(1));
        $this->assertTrue(Integer::isNotNegative(0));
        $this->assertFalse(Integer::isPositive(-1));
    }
}
