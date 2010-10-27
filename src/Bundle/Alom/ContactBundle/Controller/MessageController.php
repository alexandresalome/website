<?php

namespace Bundle\Alom\ContactBundle\Controller;

use Bundle\Alom\ContactBundle\Form\MessageForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for frontend message contact.
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class MessageController extends Controller
{
    /**
     * This page contains form for frontend user.
     */
    public function editAction()
    {
        return $this->render('ContactBundle:Message:Edit.php', array('form' => $this->getForm()));
    }

    /**
     * Frontend user posts form data to this controller
     */
    public function saveAction()
    {
        // TODO
        return $this->redirect($this->generateUrl('ContactBundle_Message_Confirmation'));
    }

    /**
     * Display the confirmation page
     */
    public function confirmationAction()
    {
        return $this->render('ContactBundle:Message:Confirmation.php');
    }

    /**
     * Get the contact form for message post.
     *
     * @param Bundle\Alom\ContactBundle\Entity\Message $object Message to fill
     *        form from
     *
     * @return Bundle\Alom\ContactBundle\Form\MessageForm
     */
    protected function getForm($object = null)
    {
        return new MessageForm('message', $object, $this->container->get('validator'));
    }
}
