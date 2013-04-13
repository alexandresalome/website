<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Client;
use Tests\AppBundle\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testIndexAsAdmin()
    {
        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/books');
        $this->assertEquals(1, $crawler->filter('a.button-add')->count(), "Add button is present");
        $this->assertContains('Disabled book', $this->client->getResponse()->getContent());

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

        $crawler = $this->client->request('GET', '/books');
        $this->assertEquals($crawler->filter('a.button-add')->count(), 0, "No add button");
        $this->assertNotContains("Disabled book", $this->client->getResponse()->getContent());

        // Enable/Disable
        $filter = $crawler->filter('a:contains("Enable")');
        $this->assertEquals(0, $filter->count());
        $filter = $crawler->filter('a:contains("Disable")');
        $this->assertEquals(0, $filter->count());
    }

    public function testEnable()
    {

        $book = $this->createFixture('test-book', array(
            'enabled' => false
        ));

        $this->client->request('GET', '/');

        $this->client->request('GET', '/books/enable/' . $book->getId());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/books/enable/' . $book->getId());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/books'));
    }

    public function testDisable()
    {

        $book = $this->createFixture('test-book');

        $this->client->request('GET', '/');

        $this->client->request('GET', '/books/disable/' . $book->getId());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $this->client->request('GET', '/books/disable/' . $book->getId());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('/books'));
    }

    public function testEdit()
    {

        $book = $this->createFixture('test-book');

        $crawler = $this->client->request('GET', '/books/edit/'.$book->getId());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->isRedirect('http://localhost/login'));

        $this->client->connect('admin', 'admin');

        $crawler = $this->client->request('GET', '/books/edit/'.$book->getId());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('form#book-edit input[type=submit]')->form(array(
            'book[slug]'        => 'test-edit',
            'book[description]' => 'Test Edit'
        ));

        $crawler = $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect('/books/edit/'.$book->getId()));

        $this->client->request('GET', '/books');
        $this->assertContains('Test Edit', $this->client->getResponse()->getContent());
    }

    private function createFixture($id, array $options = array())
    {
        $options = array_merge(array(
            'enabled' => true,
            'read_at' => new \DateTime(),
        ), $options);

        if (!$this->em) {
            throw new \RuntimeException('Test did not setup');
        }

        $existing = $this->em
            ->getRepository('AppBundle:Book')
            ->findOneBy(array('slug' => $id))
        ;

        if ($existing) {
            $this->em->remove($existing);
            $this->em->flush();
        }

        $book = new Book();
        $book->setTitle($id);
        $book->setSlug($id);
        $book->setDescription('Description of '.$id);
        $book->setIsActive($options['enabled']);
        $book->setReadAt($options['read_at']);
        $this->em->persist($book);
        $this->em->flush();

        $this->fixtures[] = $book;

        return $book;
    }
}
