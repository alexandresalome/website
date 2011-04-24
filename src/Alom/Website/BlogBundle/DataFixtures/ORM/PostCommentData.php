<?php

namespace Alom\Website\BlogBundle\DataFixtures\ORM;

use Alom\Website\BlogBundle\Entity\PostComment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class PostCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $commentBobbyNice = new PostComment();
        $commentBobbyNice->setFullname('Bobby Lapointe');
        $commentBobbyNice->setEmail('bobby@example.org');
        $commentBobbyNice->setBody('What a nice article !');
        $commentBobbyNice->setPost($manager->getRepository('AlomBlogBundle:Post')->findOneBySlug('Blog-Opening'));

        $manager->persist($commentBobbyNice);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
