<?php

namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Tests\AppBundle\Client;

abstract class WebTestCase extends BaseWebTestCase
{
    protected $client;
    protected $em;
    protected $fixtures = array();

    public function setUp()
    {
        $this->client = self::createClient();
        $this->em = $this->getEntityManager($this->client);
    }

    public function tearDown()
    {
        foreach ($this->fixtures as $fixture) {
            $fixture = $this->em->merge($fixture);
            $this->em->remove($fixture);
        }

        $this->em->flush();
        $this->fixtures = array();
    }

    public function assertTextSimilar($left, $right, $message = '')
    {
        $left  = preg_replace('/\w+/', ' ', trim($left));
        $right = preg_replace('/\w+/', ' ', trim($right));

        return $this->assertEquals($left, $right, $message);
    }

    protected function getEntityManager(Client $client)
    {
        return $client->getContainer()->get('doctrine')->getManager();
    }
}
