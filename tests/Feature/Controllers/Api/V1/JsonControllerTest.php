<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Services\JsonService;
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
     *  Test validate object properties
     */
    public function testValidateObj()
    {
        $json = '{"0": [{"id": 10, "title": "House", "children": [], "parent_id": null}]}';
        $response = $this->getJson('/api/v1/json/' . $json);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Missing property: ' . JsonService::LEVEL,
        ]);
    }

    /**
     *  Test validate value of id property
     */
    public function testValidateIDProperty()
    {
        $json = '{"0": [{"id": "try", "title": "House", "level": 0, "children": [], "parent_id": null}]}';
        $response = $this->getJson('/api/v1/json/' . $json);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Invalid ID value',
        ]);
    }

    /**
     *  Test validate value of level property
     */
    public function testValidateLevelProperty()
    {
        $json = '{"0": [{"id": 10, "title": "House", "level": -1, "children": [], "parent_id": null}]}';
        $response = $this->getJson('/api/v1/json/' . $json);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Invalid level value',
        ]);
    }

    /**
     *  Test validate value of children property
     */
    public function testValidateChildrenProperty()
    {
        $json = '{"0": [{"id": 10, "title": "House", "level": 0, "children": 2, "parent_id": null}]}';
        $response = $this->getJson('/api/v1/json/' . $json);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJson([
            'success' => false,
            'message' => 'Invalid children value',
        ]);
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
        $json = '{"0": [{"id": 10, "title": "House", "level": 0, "children": [], "parent_id": null}], "1": [{"id": 12,
        "title": "Red Roof", "level": 1, "children": [], "parent_id": 10}, {"id": 18, "title": "Blue Roof", "level": 1,
        "children": [], "parent_id": 10}, {"id": 13, "title": "Wall", "level": 1, "children": [], "parent_id": 10}],
        "2": [{"id": 17, "title": "Blue Window", "level": 2, "children": [], "parent_id": 12}, {"id": 16, "title": "Door",
        "level": 2, "children": [], "parent_id": 13}, {"id": 15, "title": "Red Window", "level": 2, "children": [],
        "parent_id": 12}]}';

        $response = $this->getJson('/api/v1/json/' . str_replace(PHP_EOL, '', $json));

        $children = str_replace(PHP_EOL, '', '[{"id": 12, "title": "Red Roof", "level": 1,
        "children": [{"id": 17, "title": "Blue Window", "level": 2, "children": [], "parent_id": 12}, {"id": 15,
        "title": "Red Window", "level": 2, "children": [], "parent_id": 12}], "parent_id": 10},
        {"id": 18, "title": "Blue Roof", "level": 1, "children": [],
        "parent_id": 10}, {"id": 13, "title": "Wall", "level": 1, "children": [{"id": 16, "title": "Door", "level": 2,
        "children": [], "parent_id": 13}], "parent_id": 10}]');


        $response->assertJson(fn (AssertableJson $json) =>
        $json->has(1)
            ->first(fn ($json) =>
            $json->where('id', 10)
                ->where('title', 'House')
                ->where('level', 0)
                ->where('children', json_decode($children, true))
                ->where('parent_id', null)
            )
        );
    }

}
