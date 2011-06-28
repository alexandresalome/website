<?php

namespace Alom\Website\MainBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    static protected $connection;

    static protected function createClient(array $options = array(), array $server = array())
    {
        $client = parent::createClient($options, $server);
        $client->getContainer()->get('session')->clear();
        if (null === self::$connection) {
            self::$connection = $client->getContainer()->get('doctrine.dbal.default_connection');
        } else {
            self::$connection->rollback();
            $client->getContainer()->set('doctrine.dbal.default_connection', self::$connection);
        }
        self::$connection->beginTransaction();

        return $client;
    }

    public function assertTextSimilar($left, $right, $message = '') {
        $left  = preg_replace('/\w+/', ' ', trim($left));
        $right = preg_replace('/\w+/', ' ', trim($right));

        return $this->assertEquals($left, $right, $message);
    }
}
