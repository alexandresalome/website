<?php
namespace Alom\Website\BlogBundle\Tests\Controller;

use Alom\Website\MainBundle\Test\WebTestCase;

class BlogPostTest extends WebTestCase
{
    public function testIndexAsAdmin()
    {
        $client = $this->createClient();
        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/blog');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 1, "Add button is present");
        $this->assertEquals($crawler->filter('a.button-hidden')->count(), 2, "Two inactive posts");
    }

    /**
     * @depends testIndexAsAdmin
     */
    public function testIndexAsAnonymous()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/blog');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 0, "No add button");
        $this->assertEquals($crawler->filter('a.button-hidden')->count(), 0, "No inactive post");
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

        // Check page title
        $this->assertEquals($crawler->filter('#content h1')->count(), 1);
        $this->assertContains('Blog Opening', $crawler->filter('#content h1')->text());

        // Previous/Next
        $this->assertEquals($crawler->filter('.blog-post-history a.previous')->count(), 0);
        $this->assertEquals($crawler->filter('.blog-post-history a.next')->count(), 1);

        // Date formating
        $this->assertTextSimilar($crawler->filter('.blog-post-date')->text(), "August 24, 2010");
    }

    public function testCorrectComment()
    {
        $client = $this->createClient();
        $client->followRedirects(false);

        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'postcomment[fullname]' => 'Bobby Commentor',
            'postcomment[email]'    => 'bobby@example.org',
            'postcomment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirected('/blog/Blog-Opening'));

        $entityManager = $client
            ->getContainer()
            ->get('doctrine.orm.default_entity_manager');

        $post = $entityManager
            ->getRepository('AlomBlogBundle:PostComment')
            ->findOneBy(array('fullname' => 'Bobby Commentor'))
        ;

        $this->assertTrue($post instanceof \Alom\Website\BlogBundle\Entity\PostComment);
        $entityManager->remove($post);
        $entityManager->flush();
    }

    /**
     * @dataProvider provideDataIncorrectFullname
     */
    public function testIncorrectFullname($fullname, $message)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/blog/Blog-Opening');
        $form = $crawler->filter('form.post-comment input[type=submit]')->form(array(
            'postcomment[fullname]' => $fullname,
            'postcomment[email]'    => 'bobby@example.org',
            'postcomment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#postcomment_fullname + ul > li')->text();
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
            'postcomment[fullname]' => 'Bobby',
            'postcomment[email]'    => $email,
            'postcomment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#postcomment_email + ul > li')->text();
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
            'postcomment[fullname]' => 'Bobby',
            'postcomment[email]'    => 'bobby@example.org',
            'postcomment[website]'  => $website,
            'postcomment[body]'     => 'Hey this is a cool website'
        ));

        $crawler = $client->submit($form);
        $error = $crawler->filter('#postcomment_website + ul > li')->text();
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
}
