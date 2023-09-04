<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    
    public function testIndex()
    {
        $query = '?q=&page=1&sizePerPage=10&sortField=id&sortOrder=desc';
        $response = $this->get('api/tasks' . $query);
        $response->dump();
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $reqBody = [
            'name' => 'Make Breakfast'
        ];

        $response = $this->post('api/tasks', $reqBody);
        $response->dump();
        $response->assertStatus(200);
    }

    public function testShow()
    {
        $response = $this->get('api/tasks/1');
        $response->dump();
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $reqBody = [
            'name' => 'Make Breakfast',
            'status' => 1
        ];

        $response = $this->put('api/tasks/1', $reqBody);
        $response->dump();
        $response->assertStatus(200);
    }

    public function testDelete()
    {
        $response = $this->delete('api/tasks/1');
        $response->dump();
        $response->assertStatus(200);
    }
}
