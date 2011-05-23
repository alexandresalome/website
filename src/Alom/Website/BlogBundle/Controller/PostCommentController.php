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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Alom\Website\BlogBundle\Entity\PostComment;
use Alom\Website\BlogBundle\Form\PostComment as PostCommentForm;

class PostCommentController extends Controller
{
    public function activateAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $comment = $em->getRepository('AlomBlogBundle:PostComment')->find($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $comment->activate();
        $em->persist($comment);
        $em->flush();

        return $this->redirect($this->generateUrl('blog_post_view', array('slug' => $comment->getPost()->getSlug())));
    }

    public function inactivateAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $comment = $em->getRepository('AlomBlogBundle:PostComment')->find($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $comment->inactivate();
        $em->persist($comment);
        $em->flush();

        return $this->redirect($this->generateUrl('blog_post_view', array('slug' => $comment->getPost()->getSlug())));
    }
}
