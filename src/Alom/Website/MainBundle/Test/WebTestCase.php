<?php

namespace Alom\Website\MainBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    static protected function createClient(array $options = array(), array $server = array())
    {
        $client = parent::createClient($options, $server);
        $client->getContainer()->get('session')->clear();

        return $client;
    }

    public function assertTextSimilar($left, $right, $message = '') {
        $left  = preg_replace('/\w+/', ' ', trim($left));
        $right = preg_replace('/\w+/', ' ', trim($right));

        return $this->assertEquals($left, $right, $message);
    }
}
