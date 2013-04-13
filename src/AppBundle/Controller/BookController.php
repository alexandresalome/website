<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\Type\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookController extends Controller
{
    public function listAction()
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList($isAdmin);

        return $this->render('::Book/list.html.twig', array(
            'books' => $books
        ));
    }

    public function editAction(Request $request, $id = null)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        if (null !== $id) {
            $book = $em->getRepository('AppBundle:Book')->find($id);
            if (! $book) {
                throw new NotFoundHttpException("No book was found");
            }
        } else {
            $book = new Book();
        }

        $form    = $this->createForm(BookType::class, $book);

        if ($form->handleRequest($request)->isValid()) {
            $this->processUpload($book);
            $em->persist($book);
            $em->flush();

            return $this->redirect($this->generateUrl('book_edit', array('id' => $book->getId())));
        }

        return $this->render('::Book/edit.html.twig', array(
            'form' => $form->createView(),
            'book' => $book
        ));
    }

    public function deleteAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Book');
        $book = $repository->findOneBy(array('id' => $id));

        $this->removeUpload($book);
        $em->remove($book);
        $em->flush();

        return $this->redirect($this->generateUrl('book_list'));

    }

    public function disableAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em
            ->getRepository('AppBundle:Book')
            ->find($id)
        ;

        if (null === $book) {
            throw new NotFoundHttpException(sprintf('The book #%s was not found', $id));
        }

        $book->disable();
        $em->persist($book);
        $em->flush();

        return $this->redirect($this->generateUrl('book_list'));
    }

    public function enableAction($id)
    {
        if (! $this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $book = $em
            ->getRepository('AppBundle:Book')
            ->find($id)
        ;

        if (null === $book) {
            throw new NotFoundHttpException(sprintf('The book #%s was not found', $id));
        }

        $book->enable();
        $em->persist($book);
        $em->flush();

        return $this->redirect($this->generateUrl('book_list'));
    }

    protected function removeUpload(Book $book)
    {
        if ($book->getIllustration()) {
            $storage = $this->get('upload_storage');
            $storage->remove($book->getIllustration(), 'book');
        }
    }

    protected function processUpload(Book $book)
    {
        if ($book->hasIllustrationUpload()) {
            $upload  = $book->getIllustrationUpload();
            $this->removeUpload($book);

            $storage = $this->get('upload_storage');
            $filename = $storage->addUpload($upload, 'book');

            $book->setIllustration($filename);
        }
    }
}
