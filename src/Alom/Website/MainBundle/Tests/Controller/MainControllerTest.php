<?php
namespace Alom\Website\ContentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomepage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check document structure
        $this->assertEquals($crawler->filter('title')->text(), 'Alexandre Salomé');

        $this->assertEquals($crawler->filter('h1')->count(), 1);
        $this->assertEquals($crawler->filter('h1')->text(), 'Alexandre Salomé');

        $this->assertRegExp('/coffee/', $crawler->filter('#footer')->text());
    }

    public function testSitemap()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
