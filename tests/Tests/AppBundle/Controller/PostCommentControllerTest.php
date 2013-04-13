<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\PostComment;
use Symfony\Bundle\FrameworkBundle\Client;
use Tests\AppBundle\WebTestCase;

class PostCommentControllerTest extends WebTestCase
{
    public function testList()
    {
        $this->client->request('GET', '/blog/comment/list');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/comment/list');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Blog comments (5)', $crawler->filter('#content h1')->text());
        $this->assertCount(1, $crawler->filter(".block-comment.inactive"));
    }

    public function testActivate()
    {
        $comment = $this->createPostComment('test-comment', array(
            'is_moderated' => false
        ));

        $this->client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/blog/Blog-Opening');
        $this->client->request('GET', '/blog/comment/' . $comment->getId() . '/activate');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/blog/Blog-Opening'));
     }

    public function testInactivate()
    {
        $comment = $this->createPostComment('test-comment', array(
            'is_moderated' => false
        ));

        $this->client->request('GET', '/blog/comment/' . $comment->getId() . '/inactivate');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/blog/Blog-Opening');
        $this->client->request('GET', '/blog/comment/' . $comment->getId() . '/inactivate');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/blog/Blog-Opening'));
     }

    private function createPostComment($id, array $options = array())
    {
        $options = array_merge(array(
            'is_moderated' => true,
            'post' => 'Blog-Opening',
        ), $options);

        if (!$this->em) {
            throw new \RuntimeException('Test did not setup');
        }

        $existing = $this->em
            ->getRepository('AppBundle:PostComment')
            ->findOneBy(array('fullname' => $id))
        ;

        if ($existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $post = $this->em->getRepository('AppBundle:Post')->findOneBySlug($options['post']);
        if (!$post) {
            throw new \RuntimeException(sprintf('No blog post with slug "%s".', $options['post']));
        }

        $comment = new PostComment();
        $comment->setPost($post);
        $comment->setFullname($id);
        $comment->setEmail($id.'@example.org');
        $comment->setBody($id);
        $comment->setIsModerated($options['is_moderated']);

        $this->em->persist($comment);
        $this->em->flush();

        $this->fixtures[] = $comment;

        return $comment;
    }
}
