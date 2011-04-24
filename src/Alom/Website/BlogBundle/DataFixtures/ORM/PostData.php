<?php

namespace Alom\Website\BlogBundle\DataFixtures\ORM;

use Alom\Website\BlogBundle\Entity\Post;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class PostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $post_Symfony2_Services = new Post();
        $post_Symfony2_Services->setTitle('Symfony2 - Create your services');
        $post_Symfony2_Services->setSlug('Symfony2-Create-Your-Services');
        $post_Symfony2_Services->setPublishedAt("2010-09-20 00:00:00");

        $post_Symfony2_Services->setBody('@todo');

        $manager->persist($post_Symfony2_Services);

        $post_HTTP_Caching = new Post();
        $post_HTTP_Caching->setTitle('HTTP Caching');
        $post_HTTP_Caching->setSlug('HTTP-Caching');
        $post_HTTP_Caching->setPublishedAt("2010-09-14 00:00:00");

        $post_HTTP_Caching->setBody('@todo');

        $manager->persist($post_HTTP_Caching);

        $post_Ide = new Post();
        $post_Ide->setTitle('The best IDE is your IDE');
        $post_Ide->setSlug('The-Best-IDE-Is-Your-IDE');
        $post_Ide->setPublishedAt("2010-09-08 00:00:00");

        $post_Ide->setBody('@todo');

        $manager->persist($post_Ide);

        $post_Symfony2_Cache = new Post();
        $post_Symfony2_Cache->setTitle('Symfony2 - A Performance test');
        $post_Symfony2_Cache->setSlug('Symfony2-A-Performance-Test');
        $post_Symfony2_Cache->setPublishedAt("2010-08-25 00:00:00");

        $post_Symfony2_Cache->setBody('@todo');

        $manager->persist($post_Symfony2_Cache);

        $post_Opening = new Post();
        $post_Opening->setTitle('Blog Opening');
        $post_Opening->setSlug('Blog-Opening');
        $post_Opening->setPublishedAt("2010-08-24 00:00:00");

        $post_Opening->setBody('@todo');

        $manager->persist($post_Opening);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
