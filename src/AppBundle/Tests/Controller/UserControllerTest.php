<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends TestAbstract
{
    public $words = ['first', 'phone', 'test', 'can'];
    
    protected function setUp()
    {
    }
    
    public function testIndex()
    {
        $this->loadFixtures(array('AppBundle\DataFixtures\UserData'));
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
        $this->assertTrue($decoded['dic']['countWord'] == 4);

        $this->client->request('GET', '/dictionaries/'.$did.'/words');
        $decoded = $this->getArray();

    }
}
