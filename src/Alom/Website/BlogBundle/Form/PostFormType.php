<?php

namespace Alom\Website\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $bodyType = new TextareaType();
        $builder->add('title');
        $builder->add('slug');
        $builder->add('body');
        $builder->add('metaDescription');
        $builder->add('publishedAt');
        $builder->add('isActive');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Alom\Website\BlogBundle\Entity\Post'
        );
    }

    public function getName()
    {
        return 'post';
    }
}
