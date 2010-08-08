<?php

namespace Bundle\Alom\ContactBundle\Model;

use Symfony\Components\Templating\Engine;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Swift_Message;

class ContactHandler
{
    /** @var Symfony\Components\Templating\Engine */
    protected $engine;
    /** @var Doctrine\ORM\EntityManager */
    protected $entityManager;
    /** @var Swift_Mailer */
    protected $mailer;
    /** @var Array */
    protected $options = array();

    public function __construct(Engine $engine, EntityManager $entityManager, Swift_Mailer $mailer) {
        $this->engine = $engine;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    public function setSender($email, $name = null)
    {
        $this->options['sender.email'] = $email;
        $this->options['sender.name']  = $name;
    }

    public function setNotified($email, $name = null)
    {
        $this->options['notified.email'] = $email;
        $this->options['notified.name']  = $name;
    }

    public function setConfirmation($subject, $template)
    {
        $this->options['confirmation.subject']   = $subject;
        $this->options['confirmation.template']  = $template;
    }

    public function setNotification($subject, $template)
    {
        $this->options['notification.subject']   = $subject;
        $this->options['notification.template']  = $template;
    }

    public function persistAndSend($contact)
    {
        // Persist
        //$this->entityManager->persist($contact);

        $message = new Swift_Message();
        $message->setFrom($this->getOption('sender.email'), $this->getOption('sender.name'));

        // Confirmation
        $message
            ->setSubject($this->getOption('confirmation.subject'))
            ->setTo($contact->getEmail(), $contact->getFullname())
            ->setBody($this->engine->render($this->getOption('confirmation.template'), array('contact' => $contact)), 'text/html')
        ;
        $this->mailer->send($message);

        // Notification
        $message
            ->setSubject($this->getOption('notification.subject'))
            ->setTo($this->getOption('notified.email'), $this->getOption('notified.name'))
            ->setBody($this->engine->render($this->getOption('notification.template'), array('contact' => $contact)), 'text/html')
        ;

        $this->mailer->send($message);
    }

    /**
     * Get an option
     *
     * @param String  $name     Name of the option to get
     * @param Boolean $required If set to false, return the default value.
     * @param mixed   $default  Default value to return if not required
     *
     * @return mixed
     *
     * @throws Throws exception when the option is not defined and required.
     */
    protected function getOption($name, $required = true, $default = null)
    {
        if (!isset($this->options[$name])) {
            throw new Exception("Contact handler requires the option $name");
        }

        return $this->options[$name];
    }
}
