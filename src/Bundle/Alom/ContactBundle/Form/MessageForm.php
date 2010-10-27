<?php
namespace Bundle\Alom\ContactBundle\Form;

use Symfony\Component\Form\Form as Form;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Form of message
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com
 */
class MessageForm extends Form
{
    public function __construct($name, $object, ValidatorInterface $validator, array $options = array())
    {
        parent::__construct($name, $object, $validator, $options);

        $this->add(new \Symfony\Component\Form\TextField('name'));
        $this->add(new \Symfony\Component\Form\TextField('email'));
        $this->add(new \Symfony\Component\Form\TextField('subject'));
        $this->add(new \Symfony\Component\Form\TextareaField('body'));
    }
}
