<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

$em = $this->getEntityManager();

$post_Symfony2_Services = new Bundle\Alom\BlogBundle\Entity\Post();
$post_Symfony2_Services->setTitle('Symfony2 - Create your services');
$post_Symfony2_Services->setSlug('Symfony2-Create-Your-Services');
$post_Symfony2_Services->setPublishedAt("2010-09-20 00:00:00");

$post_Symfony2_Services->setBody('@todo');


$post_HTTP_Caching = new Bundle\Alom\BlogBundle\Entity\Post();
$post_HTTP_Caching->setTitle('HTTP Caching');
$post_HTTP_Caching->setSlug('HTTP-Caching');
$post_HTTP_Caching->setPublishedAt("2010-09-14 00:00:00");

$post_HTTP_Caching->setBody('@todo');

$post_Ide = new Bundle\Alom\BlogBundle\Entity\Post();
$post_Ide->setTitle('The best IDE is your IDE');
$post_Ide->setSlug('The-Best-IDE-Is-Your-IDE');
$post_Ide->setPublishedAt("2010-09-08 00:00:00");

$post_Ide->setBody('@todo');

$post_Symfony2_Cache = new Bundle\Alom\BlogBundle\Entity\Post();
$post_Symfony2_Cache->setTitle('Symfony2 - A Performance test');
$post_Symfony2_Cache->setSlug('Symfony2-A-Performance-Test');
$post_Symfony2_Cache->setPublishedAt("2010-08-25 00:00:00");

$post_Symfony2_Cache->setBody('@todo');

$post_Opening = new Bundle\Alom\BlogBundle\Entity\Post();
$post_Opening->setTitle('Blog Opening');
$post_Opening->setSlug('Blog Opening');
$post_Opening->setPublishedAt("2010-08-24 00:00:00");

$post_Opening->setBody('@todo');
