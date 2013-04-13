<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\PostComment;
use AppBundle\Form\Type\PostCommentType;
use AppBundle\Form\Type\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends Controller
{
    public function viewAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Post');

        $fetchModerated = $this->isGranted('ROLE_ADMIN');
        $post = $repository->findOneBySlugWithRelated($slug, $fetchModerated);
        if (null === $post) {
            throw new NotFoundHttpException("Blog post with slug \"$slug\" not found");
        }

        $form    = $this->createForm(PostCommentType::class);

        if ($form->handleRequest($request)->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $em->persist($comment);
            $em->flush();

            // send mail
            $message = \Swift_Message::newInstance()
                ->setSubject('New comment on article "' . $post->getTitle() . '" by ' . $comment->getFullname())
                ->setFrom($this->container->getParameter('mailer_user'))
                ->setTo($this->container->getParameter('comment_mail'))
                ->setBody($this->renderView('::PostComment/email.txt.twig', array('comment' => $comment)))
            ;
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('post_comment_confirmation', 'Your comment was successfully posted');

            return $this->redirect($this->generateUrl('post_view', array('slug' => $slug)). '#post-comment');
        }

        return $this->render('::Post/view.html.twig', array(
            'post'                    => $post,
            'postCommentConfirmation' => $this->get('session')->getFlashBag()->get('post_comment_confirmation'),
            'commentForm'             => $form->createView()
        ));
    }

    public function listAction($year = null)
    {
        $fetchInactive = $this->isGranted('ROLE_ADMIN');

        $posts = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Post')
            ->fetchAllOrderedByDate($fetchInactive)
        ;

        if (count($posts) == 0) {
            throw new NotFoundHttpException("No blog post was found");
        }

        return $this->render('::Post/list.html.twig', array(
            'year' => $year,
            'posts' => $posts
        ));
    }

    public function editAction(Request $request, $id = null)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $form = $this->createForm(PostType::class);

        if (null !== $id) {
            $post = $em->getRepository('AppBundle:Post')->findOneBy(array('id' => $id));
            if (! $post) {
                throw new NotFoundHttpException("No blog post was found");
            }
        } else {
            $post = new Post();
        }

        $form->setData($post);

        if ($form->handleRequest($request)->isValid()) {
            $post->setBodyHtml($this->get('rst2html')->convert($post->getBody()));
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('::Post/edit.html.twig', array(
            'post' => $post,
            'form' => $form->createView()
        ));
    }

    public function enableAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $post->enable();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('post_view', array('slug' => $post->getSlug())));
    }

    public function disableAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $post->disable();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('post_view', array('slug' => $post->getSlug())));
    }

    public function deleteAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl('post_list'));
    }

    public function markdownPreviewAction(Request $request)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($request->getMethod() === 'POST') {
            $markdown = $request->request->get('markdown');
        }

        $content = $this->get('rst2html')->convert($markdown);

        return new Response($content);
    }

    public function rssAction($token)
    {
        if ($token !== $this->container->getParameter('rss_token')) {
            throw new NotFoundHttpException("Token is not valid");
        }
        $posts = $this
            ->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->fetchAllOrderedByDate(false, false)
        ;

        return $this->render('::Post/rss.xml.twig', array(
            'posts' => $posts
        ));
    }
}
