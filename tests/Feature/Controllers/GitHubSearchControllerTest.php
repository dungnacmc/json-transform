<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GitHubSearchControllerTest extends TestCase
{
    /**
     * Test Index page response
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertViewIs('pagination.index');
        $response->assertViewHas('total_count');
    }
}
