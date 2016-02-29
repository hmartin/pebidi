<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class WordControllerTest extends TestAbstract
{
    protected function setUp()
    {
    }
    
    public function testIndex()
    {
        $this->loadFixtures(array('AppBundle\DataFixtures\WordData'));
        $this->client = static::createClient();
        $data = [ 'data' => [
            ['w' => 'can', 'category' => 'verb', 'concat' => 'pouvoir, réussir'],
            ['w' => 'can', 'category' => 'noum', 'concat' => 'cannette']
            ]];
        
        $this->client->request('POST', '/words/improves', $data);
        $decoded = $this->getArray();
        
    }
}
