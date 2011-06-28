<?php
namespace Alom\Website\BlogBundle\Tests\Controller;

use Alom\Website\MainBundle\Test\WebTestCase;

class PostCommentControllerTest extends WebTestCase
{
    public function testActivate()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $comment = $this->findPostComment($client, 'Spam Robot');

        $client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Blog-Opening'));
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

        $client->request('GET', '/blog/comment/' . $comment->getId() . '/inactivate');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Blog-Opening'));
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
            ->getRepository('AlomBlogBundle:PostComment')
            ->findOneBy(array('fullname' => $fullname))
        ;
    }
}
