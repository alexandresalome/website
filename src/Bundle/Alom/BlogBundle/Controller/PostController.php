<?php

namespace Bundle\Alom\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            throw new NotFoundHttpException("Blog post with slug \"$slug\" not found");
        }

        return $this->render('BlogBundle:Post:View.php', array('post' => $post));
    }

    /**
     * List all posts
     *
     * @param Integer $year Year to display (default = all)
     */
    public function listAction($year = null) {
        $posts = $this->container->get('blog.helper.post')->getList($year);

        if (count($posts) == 0) {
            throw new NotFoundHttpException("No blog post was found");
        }

        $response = $this->render('BlogBundle:Post:List.php', array('year' => $year, 'posts' => $posts));

        // Cache public=10
        $response->headers->getCacheControl()->setPublic(10);
        $response->headers->getCacheControl()->setSharedMaxAge(10);

        return $response;
    }
}
