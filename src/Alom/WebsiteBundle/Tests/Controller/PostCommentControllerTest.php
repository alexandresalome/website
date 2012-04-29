<?php
namespace Alom\WebsiteBundle\Tests\Controller;

use Alom\WebsiteBundle\Test\WebTestCase;

class PostCommentControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = self::createClient();
        $client->request('GET', '/blog/comment/list');

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/comment/list');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Blog comments (5)', $crawler->filter('#content h1')->text());
        $this->assertCount(1, $crawler->filter(".block-comment.inactive"));
    }

    public function testActivate()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $comment = $this->findPostComment($client, 'Spam Robot');

        $client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/blog/Blog-Opening');
        $client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/blog/Blog-Opening'));
     }

    public function testInactivate()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $comment = $this->findPostComment($client, 'Henry Turbino');

        $client->request('GET', '/blog/comment/' . $comment->getId() . '/inactivate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/blog/Blog-Opening');
        $client->request('GET', '/blog/comment/' . $comment->getId() . '/inactivate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/blog/Blog-Opening'));
     }

    protected function getEntityManager($client)
    {
        return $client
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager');
    }

    protected function findPostComment($client, $fullname)
    {
        return $this->getEntityManager($client)
            ->getRepository('AlomWebsiteBundle:PostComment')
            ->findOneBy(array('fullname' => $fullname))
        ;
    }
}
