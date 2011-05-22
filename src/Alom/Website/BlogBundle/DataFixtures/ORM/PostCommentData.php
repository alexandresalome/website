<?php

namespace Alom\Website\BlogBundle\DataFixtures\ORM;

use Alom\Website\BlogBundle\Entity\PostComment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class PostCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $data = $this->getData();

        foreach ($data as $postSlug => $commentsData) {
            $post = $manager->getRepository('AlomBlogBundle:Post')->findOneBySlug($postSlug);
            foreach ($commentsData as $commentData) {
                $comment = new PostComment();
                $comment->setFullname($commentData['fullname']);
                $comment->setEmail($commentData['email']);
                $comment->setIsModerated($commentData['isModerated']);
                $comment->setBody($commentData['body']);
                $comment->setWebsite(isset($commentData['website']) ? $commentData['website'] : null);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }


        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

    protected function getData()
    {
        return array(
            'Blog-Opening' => array(
                array(
                    'fullname'    => 'Henry Turbino',
                    'email'       => 'henry@example.org',
                    'isModerated' => true,
                    'body'        => 'OK, let\'s see next articles, because ' .
                                  'an article about opening is very, very ' .
                                  'useless'
                ), array(
                    'fullname'    => 'Bobby Lapointe',
                    'email'       => 'bobby@example.org',
                    'isModerated' => true,
                    'body'        => 'Enlarge your penis'
                )
            ),
            'HTTP-Cache' => array(
                array(
                    'fullname'    => 'Jacky Leturbo',
                    'email'       => 'jacky@example.org',
                    'isModerated' => true,
                    'body'        => 'Are you sure you finished this article ?'
                )
            ),
            'Symfony2-Create-Your-Services' => array(
                array(
                    'fullname'    => 'Bobby Lapointe',
                    'email'       => 'bobby@example.org',
                    'isModerated' => true,
                    'body'        => 'You should have talked about how to handle '.
                                     'debug/non-debug construction.' .
                                     "\n" .
                                     'Another interesting part in Symfony2 is ' .
                                     'the execution difference between ' .
                                     'production and and development environment' .
                                     "\n" .
                                  'Waiting for new articles ;)'
                ), array(
                    'fullname'    => 'Jimmy Laflèche',
                    'email'       => 'jimmy@example.org',
                    'isModerated' => true,
                    'website'     => 'http://jimmy.example.org',
                    'body'        => 'What a nice article !' .
                                     "\n" .
                                     'Read you next time !'
                )
            )
        );
    }
}
