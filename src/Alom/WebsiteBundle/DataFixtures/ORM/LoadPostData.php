<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\WebsiteBundle\DataFixtures\ORM;

use Alom\WebsiteBundle\Entity\Post;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $post_Symfony2_Services = new Post();
        $post_Symfony2_Services->setTitle('Symfony2 - Create your services');
        $post_Symfony2_Services->setSlug('Symfony2-Create-Your-Services');
        $post_Symfony2_Services->setPublishedAt("2010-09-20 00:00:00");
        $post_Symfony2_Services->setIsActive(true);

        $post_Symfony2_Services->setBody('@todo');
        $post_Symfony2_Services->setBodyHtml('<p>@todo</p>');
        $post_Symfony2_Services->setMetaDescription('@todo');

        $manager->persist($post_Symfony2_Services);

        $post_HTTP_Caching = new Post();
        $post_HTTP_Caching->setTitle('HTTP Caching');
        $post_HTTP_Caching->setSlug('HTTP-Caching');
        $post_HTTP_Caching->setPublishedAt("2010-09-14 00:00:00");
        $post_HTTP_Caching->setIsActive(true);

        $post_HTTP_Caching->setBody('@todo');
        $post_HTTP_Caching->setBodyHtml('<p>@todo</p>');
        $post_HTTP_Caching->setMetaDescription('@todo');

        $manager->persist($post_HTTP_Caching);

        $post_Ide = new Post();
        $post_Ide->setTitle('The best IDE is your IDE');
        $post_Ide->setSlug('The-Best-IDE-Is-Your-IDE');
        $post_Ide->setPublishedAt("2010-09-08 00:00:00");
        $post_Ide->setIsActive(false);

        $post_Ide->setBody('@todo');
        $post_Ide->setBodyHtml('<p>@todo</p>');
        $post_Ide->setMetaDescription('@todo');

        $manager->persist($post_Ide);

        $post_Symfony2_Cache = new Post();
        $post_Symfony2_Cache->setTitle('Symfony2 - A Performance test');
        $post_Symfony2_Cache->setSlug('Symfony2-A-Performance-Test');
        $post_Symfony2_Cache->setPublishedAt("2010-08-25 00:00:00");
        $post_Symfony2_Cache->setIsActive(false);

        $post_Symfony2_Cache->setBody('@todo');
        $post_Symfony2_Cache->setBodyHtml('<p>@todo</p>');
        $post_Symfony2_Cache->setMetaDescription('@todo');

        $manager->persist($post_Symfony2_Cache);

        $post_Opening = new Post();
        $post_Opening->setTitle('Blog Opening');
        $post_Opening->setSlug('Blog-Opening');
        $post_Opening->setPublishedAt("2010-08-24 00:00:00");
        $post_Opening->setIsActive(true);

        $post_Opening->setBody('@todo');
        $post_Opening->setBodyHtml('<p>@todo</p>');
        $post_Opening->setMetaDescription('Article about blog opening');

        $manager->persist($post_Opening);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
