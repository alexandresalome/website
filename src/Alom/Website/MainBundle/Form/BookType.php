<?php
namespace Alom\Website\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BookType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('slug')
            ->add('description')
            ->add('readAt')
            ->add('isActive', null, array('required' => false))
            ->add('illustrationUpload', 'file', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'book';
    }
}