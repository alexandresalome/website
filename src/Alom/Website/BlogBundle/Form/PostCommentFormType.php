<?php

namespace Alom\Website\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PostCommentFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('fullname');
        $builder->add('email');
        $builder->add('website');
        $builder->add('body');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Alom\Website\BlogBundle\Entity\PostComment'
        );
    }

    public function getName()
    {
        return 'postcomment';
    }
}
