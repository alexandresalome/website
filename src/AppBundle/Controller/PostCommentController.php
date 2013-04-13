<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PostComment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostCommentController extends Controller
{
    public function listAction()
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $comments = $this->getDoctrine()
            ->getRepository('AppBundle:PostComment')
            ->fetchAllOrderedByDate()
        ;

        return $this->render('::PostComment/list.html.twig', array(
            'comments' => $comments
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:PostComment')->find($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $comment->activate();
        $em->remove($comment);
        $em->flush();

        $fallbackUrl = $this->generateUrl('post_view', array('slug' => $comment->getPost()->getSlug()));

        return $this->redirect($request->headers->get('Referer', $fallbackUrl));
    }

    public function activateAction(Request $request, $id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:PostComment')->find($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $comment->activate();
        $em->persist($comment);
        $em->flush();

        $fallbackUrl = $this->generateUrl('post_view', array('slug' => $comment->getPost()->getSlug()));

        return $this->redirect($request->headers->get('Referer', $fallbackUrl));
    }

    public function inactivateAction(Request $request, $id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('AppBundle:PostComment')->find($id);

        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $comment->inactivate();
        $em->persist($comment);
        $em->flush();

        $fallbackUrl = $this->generateUrl('post_view', array('slug' => $comment->getPost()->getSlug()));

        return $this->redirect($request->headers->get('Referer', $fallbackUrl));
    }
}
