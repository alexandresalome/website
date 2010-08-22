<?php

namespace Bundle\Alom\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blog post controller
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class PostController extends Controller
{
    /**
     * Main homepage
     */
    public function viewAction($slug) {
        $post = $this->container->get('blog.helper.post')->find($slug);

        if (null === $post) {
            return $this->forward('PageBundle:Main:error404');
        }

        return $this->render('BlogBundle:Post:view', array('post' => $post));
    }

    /**
     * List all posts
     *
     * @param Integer $year Year to display (default = all)
     */
    public function listAction($year = null) {
        $posts = $this->container->get('blog.helper.post')->getList($year);

        if (count($posts) == 0) {
            return $this->forward('PageBundle:Main:error404');
        }

        $response = $this->render('BlogBundle:Post:list', array('year' => $year, 'posts' => $posts));

        // Cache public=10
        $response->headers->getCacheControl()->setPublic(10);
        $response->headers->getCacheControl()->setSharedMaxAge(10);

        return $response;
    }
}
