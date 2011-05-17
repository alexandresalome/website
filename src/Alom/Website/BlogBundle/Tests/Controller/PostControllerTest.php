<?php
namespace Alom\Website\BlogBundle\Tests\Controller;

use Alom\Website\MainBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{
    public function testPostView()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/blog/Blog-Opening');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check title
        $this->assertRegExp('/Blog Opening/', $crawler->filter('title')->text());

        // Check page title
        $this->assertEquals($crawler->filter('#content h1')->count(), 1);
        $this->assertContains('Blog Opening', $crawler->filter('#content h1')->text());

        // Previous/Next
        $this->assertEquals($crawler->filter('.blog-post-history a.previous')->count(), 0);
        $this->assertEquals($crawler->filter('.blog-post-history a.next')->count(), 1);

        // Date formating
        $this->assertTextSimilar($crawler->filter('.blog-post-date')->text(), "August 24, 2010");
    }
}
