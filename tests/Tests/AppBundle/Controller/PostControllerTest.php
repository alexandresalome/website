<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostComment;
use Tests\AppBundle\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testIndexAsAdmin()
    {
        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog');
        $this->assertEquals(1, $crawler->filter('a.button-add')->count(), "Add button is present");
        $this->assertEquals(2, $crawler->filter('li:contains("(inactive")')->count(), "Two inactive posts");
    }

    public function testIndexAsAnonymous()
    {
        $crawler = $this->client->request('GET', '/blog');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 0, "No add button");
        $this->assertEquals($crawler->filter('li:contains("(inactive")')->count(), 0, "No inactive post");
    }

    public function testPostView()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');

        // Check the response object
        $response = $this->client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check title
        $this->assertRegExp('/Blog Opening/', $crawler->filter('title')->text());
        $this->assertRegExp('/Article about blog opening/', $crawler->filter('meta[name=description]')->attr('content'));

        // Check page title
        $this->assertEquals($crawler->filter('#content h1')->count(), 1);
        $this->assertContains('Blog Opening', $crawler->filter('#content h1')->text());

        // Previous/Next
        $this->assertEquals($crawler->filter('.blog-post-history a.previous')->count(), 0);
        $this->assertEquals($crawler->filter('.blog-post-history a.next')->count(), 1);

        // Date formating
        $this->assertTextSimilar($crawler->filter('.post-date')->text(), "August 24, 2010");
    }

    public function testCorrectComment()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');

        // no comment
        $this->assertNotContains('comment-test', $this->client->getResponse()->getContent());

        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'post_comment[fullname]' => 'comment-test',
            'post_comment[email]'    => 'comment-test@example.org',
            'post_comment[body]'     => 'comment-test'
        ));

        $crawler = $this->client->submit($form);
        $this->client->followRedirects(false);
        $this->assertTrue($this->client->getResponse()->isRedirect('/blog/Blog-Opening#post-comment'));
        $this->client->followRedirect();

        // post-moderation
        $this->assertContains('comment-test', $this->client->getResponse()->getContent());

        // confirmation
        $this->assertContains('Your comment was successfully posted', $this->client->getResponse()->getContent());

        $post = $this->findPostComment('comment-test');
        $this->assertTrue($post instanceof PostComment);
        $this->fixtures[] = $post;
    }

    public function testHtmlEscaping()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'post_comment[fullname]' => 'Bobby <em>Commentor</em>',
            'post_comment[email]'    => 'bobby@example.org',
            'post_comment[body]'     => 'Hey this is a cool <a href="http://example.org">website</a>'
        ));

        $crawler = $this->client->submit($form);
        $this->client->followRedirects(false);
        $this->assertTrue($this->client->getResponse()->isRedirect('/blog/Blog-Opening#post-comment'));

        $crawler  = $this->client->request('GET', '/blog/Blog-Opening');
        $responseContent = $this->client->getResponse()->getContent();

        $this->assertFalse(strpos($responseContent, '<em>Commentor</em>'));
        $this->assertFalse(strpos($responseContent, '<a href="http://example.org">Website</a>'));
    }

    /**
     * @dataProvider provideDataIncorrectFullname
     */
    public function testIncorrectFullname($fullname, $message)
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'post_comment[fullname]' => $fullname,
            'post_comment[email]'    => 'bobby@example.org',
            'post_comment[body]'     => 'Hey this is a cool website'
        ));


        $crawler = $this->client->submit($form);
        $error = $crawler->filter('#post_comment_fullname + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectFullname()
    {
        return array(
            array('', 'This value should not be blank.'),
            array('  ', 'This value should not be blank.'),
        );
    }

    /**
     * @dataProvider provideDataIncorrectEmail
     */
    public function testIncorrectEmail($email, $message)
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'post_comment[fullname]' => 'Bobby',
            'post_comment[email]'    => $email,
            'post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $this->client->submit($form);
        $error = $crawler->filter('#post_comment_email + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectEmail()
    {
        return array(
            array('', 'This value should not be blank.'),
            array('bobby', 'This value is not a valid email address.'),
            array('bobby@laposte', 'This value is not a valid email address.'),
        );
    }

    /**
     * @dataProvider provideDataIncorrectWebsite
     */
    public function testIncorrectWebsite($website, $message)
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'post_comment[fullname]' => 'Bobby',
            'post_comment[email]'    => 'bobby@example.org',
            'post_comment[website]'  => $website,
            'post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $this->client->submit($form);
        $error = $crawler->filter('#post_comment_website + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectWebsite()
    {
        return array(
            array('http://', 'This value is not a valid URL.'),
            array('ftp://bobby', 'This value is not a valid URL.'),
        );
    }

    public function testNotModeratedNotDisplayed()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');

        $this->assertNotContains('Enlarge your penis', $this->client->getResponse()->getContent());
    }

    public function testInactivePost()
    {
        $crawler = $this->client->request('GET', '/blog/Symfony2-A-Performance-Test');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testPostNextIsActive()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');

        $nextUrl = $crawler->filter('a.next')->attr('href');
        $this->assertRegExp('/HTTP-Caching$/', $nextUrl);
    }

    public function testPostPreviousIsActive()
    {
        $crawler = $this->client->request('GET', '/blog/HTTP-Caching');

        $nextUrl = $crawler->filter('a.previous')->attr('href');
        $this->assertRegExp('/Blog-Opening$/', $nextUrl);
    }

    public function testInactiveCommentButtons()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $comment = $this->findPostComment('Spam Robot');
        $id = $comment->getId();

        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/activate"]');
        $this->assertEquals(0, $filter->count());

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/activate"]');
        $this->assertEquals(1, $filter->count());
        $this->assertEquals("Activate", $filter->text());
    }

    public function testActiveCommentButtons()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $comment = $this->findPostComment('Henry Turbino');
        $id = $comment->getId();

        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/inactivate"]');
        $this->assertEquals(0, $filter->count());

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/inactivate"]');
        $this->assertEquals(1, $filter->count());
        $this->assertEquals("Inactivate", $filter->text());
    }

    public function testEnableButtonPost()
    {
        $crawler = $this->client->request('GET', '/blog/Symfony2-A-Performance-Test');

        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(0, $filter->count());

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/Symfony2-A-Performance-Test');
        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(1, $filter->count());
    }

    public function testDisableButtonPost()
    {
        $crawler = $this->client->request('GET', '/blog/Blog-Opening');

        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(0, $filter->count());

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(1, $filter->count());
    }

    public function testEnable()
    {
        $post = $this->createPost('test-post');

        $this->client->request('GET', '/blog/' . $post->getId() . '/enable');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/blog/' . $post->getId() . '/enable');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/blog/test-post'));
    }

    public function testDisable()
    {
        $post = $this->createPost('test-post');

        $this->client->request('GET', '/blog/' . $post->getId() . '/disable');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/blog/' . $post->getId() . '/disable');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/blog/test-post'));

        $this->client->request('GET', '/blog/' . $post->getId() . '/enable');
    }

    public function testCreate()
    {
        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/create');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testEdit()
    {
        $post = $this->createPost('test-post');

        $crawler = $this->client->request('GET', '/blog/' . $post->getId() . '/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/blog/' . $post->getId() . '/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('form#post-edit input[type=submit]')->form(array(
            'post[slug]'  => 'Ouverture',
            'post[body]'  => 'WELCOME !'
        ));

        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/blog/' . $post->getId() . '/edit'));

        $this->client->request('GET', '/blog/Ouverture');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testRssInvalid()
    {
        $crawler = $this->client->request('GET', '/rss/invalid');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testRssValid()
    {
        $token = $this->client->getContainer()->getParameter('rss_token');

        $crawler = $this->client->request('GET', '/rss/'.$token);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Blog Opening', $crawler->filter('item title')->last()->text());
        $this->assertEquals('Symfony2 - Create your services', $crawler->filter('item title')->first()->text());
        $this->assertEquals(0, $crawler->filter('li:contains("Symfony2 - A Performance Test")')->count());
    }

    private function findPostComment($fullname)
    {
        return $this->getEntityManager($this->client)
            ->getRepository('AppBundle:PostComment')
            ->findOneBy(array('fullname' => $fullname))
        ;
    }

    private function findPost($slug)
    {
        return $this->getEntityManager($this->client)
            ->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $slug))
        ;
    }

    private function createPost($id, array $options = array())
    {
        $options = array_merge(array(
            'is_active' => true,
            'published_at' => new \DateTime(),
        ), $options);

        if (!$this->em) {
            throw new \RuntimeException('Test did not setup');
        }

        $existing = $this->em
            ->getRepository('AppBundle:Post')
            ->findOneBy(array('slug' => $id))
        ;

        if ($existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $post = new Post();
        $post->setTitle($id);
        $post->setSlug($id);
        $post->setBody('Body of '.$id);
        $post->setMetaDescription('Meta-description of '.$id);
        $post->setBodyHtml('<p>Body of '.$id.'</p>');
        $post->setIsActive($options['is_active']);
        $post->setPublishedAt($options['published_at']);
        $this->em->persist($post);
        $this->em->flush();

        $this->fixtures[] = $post;

        return $post;
    }

}
