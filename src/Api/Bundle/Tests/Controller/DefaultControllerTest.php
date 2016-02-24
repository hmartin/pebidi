<?php

namespace Api\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $this->client = static::createClient();

        $this->client->request('POST', '/users/emails', array('email' => 'hmartin@test.te'));
    
        $decoded = $this->getArray();
        $this->assertTrue(isset($decoded['user']['id']));
        $this->assertTrue(isset($decoded['dic']['id']));
        $uid = $decoded['user']['id'];
        $did = $decoded['dic']['id'];
        
        
        $this->client->request('POST', '/users/emails', array('email' => 'hmartin@test.te'));
        
        $decoded = $this->getArray();
        var_dump($decoded);
    }

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
