<?php
namespace Alom\Website\ContentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);
    }
}
