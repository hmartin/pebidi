<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class WordControllerTest extends TestAbstract
{
    private $wordToSuck = ['friday', 'first', 'phone'];
    
    public function testIndex()
    {
        $this->loadFixtures(array('AppBundle\DataFixtures\WordData'));
        $this->client = static::createClient();
        $data = [ 'word' => 'can', 'data' => [
            ['w' => 'can', 'category' => 'verb', 'concat' => 'pouvoir, savoir'],
            ['w' => 'can', 'category' => 'verb', 'concat' => 'virer'],
            ['w' => 'can', 'category' => 'noum', 'concat' => 'cannette'],
            ['w' => 'can of beer', 'category' => 'noum', 'concat' => 'cannette de beer']
            ]];
        
        $this->client->request('POST', '/words/improves', $data);
        $decoded = $this->getArray();
        $this->assertTrue(count($decoded) == 3);
        
        foreach ($this->wordToSuck as $w) {
            $this->client->request('GET', '/words/'.$w.'/one/from/web/suck');
            $decoded = $this->getArray();
            
            $this->assertTrue((count($decoded) > 3 && count($decoded) < 150));
        }
    }
}
