<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\JsonService;

class JsonServiceTest extends TestCase
{
    /**
     * @var JsonService
     */
    protected JsonService $jsonService;


    protected function setUp():void
    {
        $this->jsonService = new JsonService();
        parent::setUp();
    }

    /**
     * Test isJson method
     *
     * @return void
     */
    public function testIsJson()
    {
        $this->assertFalse($this->jsonService->isJson('try'));
        $this->assertIsArray($this->jsonService->isJson('[]'));
    }


    /**
     * Test setMessage method
     *
     * @return void
     */
    public function testSetMessage()
    {
        $this->assertIsArray($this->jsonService->setMessage(false, 'Error'));
    }

}
