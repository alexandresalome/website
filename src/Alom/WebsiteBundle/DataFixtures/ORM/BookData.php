<?php

namespace Alom\WebsiteBundle\DataFixtures\ORM;

use Alom\WebsiteBundle\Entity\Book;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BookData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function load($manager)
    {
        $book = new Book();
        $book->setTitle('Disabled book');
        $book->setSlug('disabled-book');
        $book->setReadAt(new \DateTime('2011-10-10'));
        $book->setDescription('Description of the disabled book');
        $book->disable();
        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Enabled book');
        $book->setSlug('enabled-book');
        $book->setReadAt(new \DateTime('2011-10-10'));
        $book->setDescription('Description of the enabled book');
        $book->enable();
        $manager->persist($book);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
