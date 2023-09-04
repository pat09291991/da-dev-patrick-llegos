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
}
