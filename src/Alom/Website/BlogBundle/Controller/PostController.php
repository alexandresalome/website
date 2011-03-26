<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\BlogBundle\Controller;

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
     * View a blog post
     *
     * @param string $slug Slug of the post to view
     *
     * @return Response
     */
    public function viewAction($slug)
    {
        $post = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AlomBlogBundle:Post')
            ->findOneBySlug($slug)
        ;

        if (null === $post) {
            throw new NotFoundHttpException("Blog post with slug \"$slug\" not found");
        }

        return $this->render('BlogBundle:Post:view.html.twig', array('post' => $post));
    }

    /**
     * List all posts.
     *
     * @param int $year Year to display (default = all)
     */
    public function listAction($year = null)
    {
        $posts = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AlomBlogBundle:Post')
            ->findAll()
        ;

        if (count($posts) == 0) {
            throw new NotFoundHttpException("No blog post was found");
        }

        return $this->render('AlomBlogBundle:Post:list.html.twig', array(
            'year' => $year,
            'posts' => $posts
        ));
    }
}
