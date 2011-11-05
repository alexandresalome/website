<?php

namespace Alom\WebsiteBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

abstract class WebTestCase extends BaseWebTestCase
{
    public function assertTextSimilar($left, $right, $message = '') {
        $left  = preg_replace('/\w+/', ' ', trim($left));
        $right = preg_replace('/\w+/', ' ', trim($right));

        return $this->assertEquals($left, $right, $message);
    }
}
