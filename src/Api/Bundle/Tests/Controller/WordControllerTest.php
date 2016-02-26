<?php

namespace Api\Bundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class WordControllerTest extends TestAbstract
{
    protected function setUp()
    {
        /*
        self::runCommand('doctrine:fixtures:load --purge-with-truncate');
        php app/console doctrine:query:sql 
                SET FOREIGN_KEY_CHECKS=0;
                TRUNCATE `Sense`;
                TRUNCATE `Word`;
                TRUNCATE `WordType`;
                TRUNCATE `Ww`;
                TRUNCATE `WwSenses`;
                SET FOREIGN_KEY_CHECKS=1;
        */
    }
    public function testIndex()
    {
        $this->loadFixtures(array('Api\Bundle\DataFixtures\WordData'));
        $this->client = static::createClient();
        $data = [ 'data' => [
            ['w' => 'can', 'category' => 'verb', 'concat' => 'pouvoir, réussir'],
            ['w' => 'can', 'category' => 'noum', 'concat' => 'cannette']
            ]];
        
        $this->client->request('POST', '/words/improves', $data);
        $decoded = $this->getArray();
        var_dump($decoded);
    }
}
