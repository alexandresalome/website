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
        $books = $this->getDoctrine()->getRepository('AlomMainBundle:Book')->getList();

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

    protected function processUpload(Book $book)
    {
        if ($book->hasIllustrationUpload()) {
            $upload  = $book->getIllustrationUpload();
            $storage = $this->get('alom.upload.storage');

            if ($book->getIllustration()) {
                $storage->remove($book->getIllustration(), 'book');
            }

            $filename = $storage->addUpload($upload, 'book');
            $book->setIllustration($filename);
        }
    }
}
