<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Alom\Website\MainBundle\Entity\Book;
use Alom\Website\MainBundle\Form\BookType;

class BookController extends Controller
{
    public function listAction()
    {
        $isAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
        $books = $this->getDoctrine()->getRepository('AlomMainBundle:Book')->getList($isAdmin);

        return $this->render('AlomMainBundle:Book:list.html.twig', array(
            'books' => $books
        ));
    }

    public function editAction($id = null)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        if (null !== $id) {
            $book = $em->getRepository('AlomMainBundle:Book')->find($id);
            if (! $book) {
                throw new NotFoundHttpException("No book was found");
            }
        } else {
            $book = new Book();
        }

        $form    = $this->createForm(new BookType(), $book);
        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $this->processUpload($book);
                $em->persist($book);
                $em->flush();

                return $this->redirect($this->generateUrl('main_book_edit', array('id' => $book->getId())));
            }
        }

        return $this->render('AlomMainBundle:Book:edit.html.twig', array(
            'form' => $form->createView(),
            'book' => $book
        ));
    }

    public function deleteAction($id)
    {
        if (! $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('AlomMainBundle:Book');
        $book = $repository->findOneBy(array('id' => $id));

        $this->removeUpload($book);
        $em->remove($book);
        $em->flush();

        return $this->redirect($this->generateUrl('main_book_list'));

    }

    public function disableAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $book = $em
            ->getRepository('AlomMainBundle:Book')
            ->find($id)
        ;

        if (null === $book) {
            throw new NotFoundHttpException(sprintf('The book #%s was not found', $id));
        }

        $book->disable();
        $em->persist($book);
        $em->flush();

        return $this->redirect($this->generateUrl('main_book_list'));
    }

    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $book = $em
            ->getRepository('AlomMainBundle:Book')
            ->find($id)
        ;

        if (null === $book) {
            throw new NotFoundHttpException(sprintf('The book #%s was not found', $id));
        }

        $book->enable();
        $em->persist($book);
        $em->flush();

        return $this->redirect($this->generateUrl('main_book_list'));
    }

    protected function removeUpload(Book $book)
    {
        if ($book->getIllustration()) {
            $storage = $this->get('alom.upload.storage');
            $storage->remove($book->getIllustration(), 'book');
        }
    }

    protected function processUpload(Book $book)
    {
        if ($book->hasIllustrationUpload()) {
            $upload  = $book->getIllustrationUpload();
            $this->removeUpload($book);

            $storage = $this->get('alom.upload.storage');
            $filename = $storage->addUpload($upload, 'book');

            $book->setIllustration($filename);
        }
    }
}
