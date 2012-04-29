<?php

namespace Alom\WebsiteBundle\Form\Type;

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
            ->add('externalLink', 'text', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'alom_website_book';
    }
}
