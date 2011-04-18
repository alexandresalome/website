<?php
namespace Alom\Website\BlogBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{
    public function testPostView()
    {
        $client = $this->createClient();
        $em = $client->getContainer()->get('doctrine.orm.entity_manager');

        $crawler = $client->request('GET', '/blog/Blog-Opening');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check title
        $this->assertRegExp('/Blog Opening/', $crawler->filter('title')->text());

        // Check page title
        $this->assertEquals($crawler->filter('#content h1')->count(), 1);
        $this->assertContains('Blog Opening', $crawler->filter('#content h1')->text());

        // Has no previous
    }
}
