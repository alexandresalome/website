<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Alom\WebsiteBundle\Entity\Post;
use Alom\WebsiteBundle\Entity\PostComment;
use Alom\WebsiteBundle\Form\PostCommentFormType;
use Alom\WebsiteBundle\Form\PostFormType;

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
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AlomWebsiteBundle:Post');

        $fetchModerated = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $post = $repository->findOneBySlugWithRelated($slug, $fetchModerated);
        if (null === $post) {
            throw new NotFoundHttpException("Blog post with slug \"$slug\" not found");
        }

        $form    = $this->get('form.factory')->create(new PostCommentFormType());
        $request = $this->get('request');

        if ($request->getMethod() === 'POST') {
            $comment = new PostComment();
            $form->bindRequest($request);
            if ($form->isValid()) {
                $comment = $form->getData();
                $comment->setPost($post);
                $em->persist($comment);
                $em->flush();

                // send mail
                $message = \Swift_Message::newInstance()
                    ->setSubject('New comment on article "' . $post->getTitle() . '" by ' . $comment->getFullname())
                    ->setFrom($this->container->getParameter('mailer_user'))
                    ->setTo($this->container->getParameter('comment_mail'))
                    ->setBody($this->renderView('AlomWebsiteBundle:PostComment:email.txt.twig', array('comment' => $comment)))
                ;
                $this->get('mailer')->send($message);

                $this->get('session')->setFlash('post_comment_confirmation', 'Your comment was successfully posted');
                return $this->redirect($this->generateUrl('blog_post_view', array('slug' => $slug)). '#post-comment');
            }
        }

        return $this->render('AlomWebsiteBundle:Post:view.html.twig', array(
            'post'                    => $post,
            'postCommentConfirmation' => $this->get('session')->getFlash('post_comment_confirmation'),
            'commentForm'             => $form->createView()
        ));
    }

    /**
     * List all posts.
     *
     * @param int $year Year to display (default = all)
     */
    public function listAction($year = null)
    {
        $fetchInactive = $this->get('security.context')->isGranted('ROLE_ADMIN');

        $posts = $this
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AlomWebsiteBundle:Post')
            ->fetchAllOrderedByDate($fetchInactive)
        ;

        if (count($posts) == 0) {
            throw new NotFoundHttpException("No blog post was found");
        }

        return $this->render('AlomWebsiteBundle:Post:list.html.twig', array(
            'year' => $year,
            'posts' => $posts
        ));
    }

    /**
     * Edit a post
     */
    public function editAction($id = null)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->get('doctrine.orm.default_entity_manager');
        $factory = $this->get('form.factory');
        $form = $factory->create(new PostFormType());

        if (null !== $id) {
            $post = $em->getRepository('AlomWebsiteBundle:Post')->findOneBy(array('id' => $id));
            if (! $post) {
                throw new NotFoundHttpException("No blog post was found");
            }
        } else {
            $post = new Post();
        }

        $form->setData($post);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $post->setBodyHtml($this->get('alom.blog.rst2html')->convert($post->getBody()));
                $em->persist($post);
                $em->flush();

                return $this->redirect($this->generateUrl('blog_post_edit', array('id' => $post->getId())));
            }
        }

        return $this->render('AlomWebsiteBundle:Post:edit.html.twig', array(
            'post' => $post,
            'form' => $form->createView()
        ));
    }

    public function enableAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AlomWebsiteBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $post->enable();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('blog_post_view', array('slug' => $post->getSlug())));
    }

    public function disableAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AlomWebsiteBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $post->disable();
        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('blog_post_view', array('slug' => $post->getSlug())));
    }

    public function deleteAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AlomWebsiteBundle:Post');
        $post = $repository->findOneBy(array('id' => $id));

        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl('blog_post_list'));
    }

    public function markdownPreviewAction()
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        if ($this->get('request')->getMethod() === 'POST') {
            $markdown = $this->get('request')->request->get('markdown');
        }

        $content = $this->get('alom.blog.rst2html')->convert($markdown);

        return new Response($content);
    }

    public function rssAction($token)
    {
        if ($token !== $this->container->getParameter('rss_token')) {
            throw new NotFoundHttpException("Token is not valid");
        }
        $posts = $this
            ->getDoctrine()
            ->getRepository('AlomWebsiteBundle:Post')
            ->fetchAllOrderedByDate()
        ;

        return $this->render('AlomWebsiteBundle:Post:rss.xml.twig', array(
            'posts' => $posts
        ));
    }
}
