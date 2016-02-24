<?php

namespace Api\Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    protected $words = ['first', 'laptop', 'phone'];

    public function testIndex()
    {
        $this->client = static::createClient();

        $this->client->request('POST', '/users/emails', array('email' => 'hmartin'.uniqid().'@test.te'));
    
        $decoded = $this->getArray();
        $this->assertTrue(isset($decoded['user']['id']));
        $this->assertTrue(isset($decoded['dic']['id']));
        $uid = $decoded['user']['id'];
        $did = $decoded['dic']['id'];
        
        foreach($this->words as $w) {
            $this->client->request('POST', '/words', array('id' => $did, 'w' => $w));
        }

        $decoded = $this->getArray();
        $this->assertTrue($decoded['dic']['countWord'] == 3);

        $this->client->request('GET', '/dictionaries/'.$did.'/words');
        $decoded = $this->getArray();
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
