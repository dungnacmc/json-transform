<?php

namespace Tests\Feature\Controllers\Api\V1;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Http\Response;

class JsonControllerTest extends TestCase
{
    /**
     * Test wrong JSON input.
     *
     * @return void
     */
    public function testWrongJson()
    {
        $response = $this->getJson('/api/v1/json/test');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Wrong JSON string',
        ]);;
    }

    /**
     * Test incorrect object input.
     *
     * @return void
     */
    public function testIncorrectObject()
    {
        $response = $this->getJson('/api/v1/json/[{}]');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Incorrect object',
        ]);;
    }

    /**
     * Test not found exception.
     *
     * @return void
     */
    public function testNotFoundException()
    {

        $response = $this->getJson('/api/v1/json');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJson(['message' => 'Not Found']);
    }

    /**
     * Test method not allowed exception.
     *
     * @return void
     */
    public function testMethodNotAllowedException()
    {

        $response = $this->postJson('/api/v1/json/[]');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED)->assertJson(['message' => 'Method Not Allowed']);
    }

    /**
     * Test transform JSON string.
     *
     * @return void
     */
    public function testTransform()
    {
        $json = '{"0": [{"id": 10, "title": "House", "level": 0, "children": [], "parent_id": null}], "1": [{"id": 12, "title": "Red Roof", "level": 1, "children": [], "parent_id": 10}, {"id": 18, "title": "Blue Roof", "level": 1, "children": [], "parent_id": 10}, {"id": 13, "title": "Wall", "level": 1, "children": [], "parent_id": 10}], "2": [{"id": 17, "title": "Blue Window", "level": 2, "children": [], "parent_id": 12}, {"id": 16, "title": "Door", "level": 2, "children": [], "parent_id": 13}, {"id": 15, "title": "Red Window", "level": 2, "children": [], "parent_id": 12}]}';
        $response = $this->getJson('/api/v1/json/' . $json);

        $response->assertJson(fn (AssertableJson $json) =>
        $json->has(1)
            ->first(fn ($json) =>
            $json->where('id', 10)
                ->where('title', 'House')
                ->where('level', 0)
                ->etc()
            )
        );
    }

}
