<?php
namespace Alom\WebsiteBundle\Tests\Controller;

use Alom\WebsiteBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testIndexAsAdmin()
    {
        $client = $this->createClient();
        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog');
        $this->assertEquals(1, $crawler->filter('a.button-add')->count(), "Add button is present");
        $this->assertEquals(2, $crawler->filter('li:contains("(inactive")')->count(), "Two inactive posts");
    }

    /**
     * @depends testIndexAsAdmin
     */
    public function testIndexAsAnonymous()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/blog');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 0, "No add button");
        $this->assertEquals($crawler->filter('li:contains("(inactive")')->count(), 0, "No inactive post");
    }

    public function testPostView()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/blog/Blog-Opening');

        // Check the response object
        $response = $client->getResponse();
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
        $client = $this->createClient();
        $client->followRedirects(false);

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'alom_website_post_comment[fullname]' => 'Bobby Commentor',
            'alom_website_post_comment[email]'    => 'bobby@example.org',
            'alom_website_post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Blog-Opening#post-comment'));
        $client->followRedirect();

        // post-moderation
        $this->assertContains('Bobby Commentor', $client->getResponse()->getContent());

        // confirmation
        $this->assertContains('Your comment was successfully posted', $client->getResponse()->getContent());

        $post = $this->findPostComment($client, 'Bobby Commentor');
        $this->assertTrue($post instanceof \Alom\WebsiteBundle\Entity\PostComment);
    }

    public function testHtmlEscaping()
    {
        $client = $this->createClient();
        $client->followRedirects(false);

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'alom_website_post_comment[fullname]' => 'Bobby <em>Commentor</em>',
            'alom_website_post_comment[email]'    => 'bobby@example.org',
            'alom_website_post_comment[body]'     => 'Hey this is a cool <a href="http://example.org">website</a>'
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Blog-Opening#post-comment'));

        $crawler  = $client->request('GET', '/blog/Blog-Opening');
        $responseContent = $client->getResponse()->getContent();

        $this->assertFalse(strpos($responseContent, '<em>Commentor</em>'));
        $this->assertFalse(strpos($responseContent, '<a href="http://example.org">Website</a>'));
    }

    /**
     * @dataProvider provideDataIncorrectFullname
     */
    public function testIncorrectFullname($fullname, $message)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'alom_website_post_comment[fullname]' => $fullname,
            'alom_website_post_comment[email]'    => 'bobby@example.org',
            'alom_website_post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#alom_website_post_comment_fullname + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectFullname()
    {
        return array(
            array('', 'This value should not be blank'),
            array('  ', 'This value should not be blank'),
        );
    }

    /**
     * @dataProvider provideDataIncorrectEmail
     */
    public function testIncorrectEmail($email, $message)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'alom_website_post_comment[fullname]' => 'Bobby',
            'alom_website_post_comment[email]'    => $email,
            'alom_website_post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#alom_website_post_comment_email + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectEmail()
    {
        return array(
            array('', 'This value should not be blank'),
            array('bobby', 'This value is not a valid email address'),
            array('bobby@laposte', 'This value is not a valid email address'),
            array('bobby@laposte@laposte.net', 'This value is not a valid email address'),
            array('bobby@laposté.net', 'This value is not a valid email address'),
        );
    }

    /**
     * @dataProvider provideDataIncorrectWebsite
     */
    public function testIncorrectWebsite($website, $message)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'alom_website_post_comment[fullname]' => 'Bobby',
            'alom_website_post_comment[email]'    => 'bobby@example.org',
            'alom_website_post_comment[website]'  => $website,
            'alom_website_post_comment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#alom_website_post_comment_website + ul > li')->text();
        $this->assertEquals($message, $error);
    }

    public function provideDataIncorrectWebsite()
    {
        return array(
            array('http://', 'This value is not a valid URL'),
            array('http://bobby', 'This value is not a valid URL'),
            array('http://bobby/', 'This value is not a valid URL'),
            array('ftp://bobby', 'This value is not a valid URL'),
        );
    }

    public function testNotModeratedNotDisplayed()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');

        $this->assertNotContains('Enlarge your penis', $client->getResponse()->getContent());
    }

    public function testInactivePost()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Symfony2-A-Performance-Test');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testPostNextIsActive()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');

        $nextUrl = $crawler->filter('a.next')->attr('href');
        $this->assertRegExp('/HTTP-Caching$/', $nextUrl);
    }

    public function testPostPreviousIsActive()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/HTTP-Caching');

        $nextUrl = $crawler->filter('a.previous')->attr('href');
        $this->assertRegExp('/Blog-Opening$/', $nextUrl);
    }

    public function testInactiveCommentButtons()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $comment = $this->findPostComment($client, 'Spam Robot');
        $id = $comment->getId();

        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/activate"]');
        $this->assertEquals(0, $filter->count());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/activate"]');
        $this->assertEquals(1, $filter->count());
        $this->assertEquals("Activate", $filter->text());
    }

    public function testActiveCommentButtons()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $comment = $this->findPostComment($client, 'Henry Turbino');
        $id = $comment->getId();

        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/inactivate"]');
        $this->assertEquals(0, $filter->count());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a[href$="/blog/comment/' . $id . '/inactivate"]');
        $this->assertEquals(1, $filter->count());
        $this->assertEquals("Inactivate", $filter->text());
    }

    public function testEnableButtonPost()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Symfony2-A-Performance-Test');

        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(0, $filter->count());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/Symfony2-A-Performance-Test');
        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(1, $filter->count());
    }

    public function testDisableButtonPost()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');

        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(0, $filter->count());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(1, $filter->count());
    }

    public function testEnable()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $post = $this->findPost($client, 'Symfony2-A-Performance-Test');

        $client->request('GET', '/blog/' . $post->getId() . '/enable');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/blog/' . $post->getId() . '/enable');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Symfony2-A-Performance-Test'));
    }

    public function testDisable()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $post = $this->findPost($client, 'Blog-Opening');

        $client->request('GET', '/blog/' . $post->getId() . '/disable');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/blog/' . $post->getId() . '/disable');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/blog/Blog-Opening'));

        $client->request('GET', '/blog/' . $post->getId() . '/enable');
    }

    public function testCreate()
    {
        $client = $this->createClient();
        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertCount(1, $crawler->filter('form'));
    }

    public function testEdit()
    {
        $client = $this->createClient();

        $post = $this->findPost($client, 'Blog-Opening');

        $crawler = $client->request('GET', '/blog/' . $post->getId() . '/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog/' . $post->getId() . '/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('form#post-edit input[type=submit]')->form(array(
            'alom_website_post[slug]'  => 'Ouverture',
            'alom_website_post[body]'  => 'WELCOME !'
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/blog/' . $post->getId() . '/edit'));

        $client->request('GET', '/blog/Ouverture');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testRssInvalid()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/rss/invalid');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testRssValid()
    {
        $client = $this->createClient();

        $token = $client->getContainer()->getParameter('rss_token');

        $crawler = $client->request('GET', '/rss/'.$token);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Blog Opening', $crawler->filter('item title')->last()->text());
        $this->assertEquals('Symfony2 - Create your services', $crawler->filter('item title')->first()->text());
        $this->assertEquals(0, $crawler->filter('li:contains("Symfony2 - A Performance Test")')->count());
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

    protected function findPost($client, $slug)
    {
        return $this->getEntityManager($client)
            ->getRepository('AlomWebsiteBundle:Post')
            ->findOneBy(array('slug' => $slug))
        ;
    }
}
