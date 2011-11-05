<?php
namespace Alom\Website\MainBundle\Tests\Controller;

use Alom\Website\MainBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testIndexAsAdmin()
    {
        $client = $this->createClient();
        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/books');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 1, "Add button is present");
        $this->assertContains('Disabled book', $client->getResponse()->getContent());

        // Enable/Disable
        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(1, $filter->count());
        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(1, $filter->count());
    }

    /**
     * @depends testIndexAsAdmin
     */
    public function testIndexAsAnonymous()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/books');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 0, "No add button");
        $this->assertNotContains("Disabled book", $client->getResponse()->getContent());

        // Enable/Disable
        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(0, $filter->count());
        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(0, $filter->count());
    }

    public function testEnable()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $post = $this->findBook($client, 'disabled-book');

        $client->request('GET', '/books/enable/' . $post->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/books/enable/' . $post->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/books'));
    }

    public function testDisable()
    {
        $client = $this->createClient();
        $client->request('GET', '/');

        $post = $this->findBook($client, 'enabled-book');

        $client->request('GET', '/books/disable/' . $post->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));

        $client->connect('admin', 'admin');

        $client->request('GET', '/books/disable/' . $post->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('/books'));
    }

    public function testEdit()
    {
        $client = $this->createClient();

        $book = $this->findBook($client, 'enabled-book');

        $crawler = $client->request('GET', '/books/edit/'.$book->getId());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->connect('admin', 'admin');

        $crawler = $client->request('GET', '/books/edit/'.$book->getId());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $form = $crawler->filter('form#book-edit input[type=submit]')->form(array(
            'book[slug]'        => 'test-edit',
            'book[description]' => 'Test Edit'
        ));

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/books/edit/'.$book->getId()));

        $client->request('GET', '/books');
        $this->assertContains('Test Edit', $client->getResponse()->getContent());
    }

    protected function getEntityManager($client)
    {
        return $client
            ->getContainer()
            ->get('doctrine')->getEntityManager();
    }

    protected function findBook($client, $slug)
    {
        return $this->getEntityManager($client)
            ->getRepository('AlomMainBundle:Book')
            ->findOneBy(array('slug' => $slug))
        ;
    }
}
