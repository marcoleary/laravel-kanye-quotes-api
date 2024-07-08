<?php

namespace Tests\Feature;

use Tests\TestCase;

class RootTest extends TestCase
{
    public function testApiRootLocationReturnsOkWithBasicRequest(): void
    {
        $response = $this->get('/api');

        $response->assertOk();
    }
}
