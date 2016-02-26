<?php

namespace Api\Bundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class TestAbstract extends WebTestCase
{
    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
    
    protected function getArray()
    {
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();
    
        return json_decode($content, true);
    }
    
}
