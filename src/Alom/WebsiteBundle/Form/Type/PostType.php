<?php

namespace Alom\WebsiteBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('title');
        $builder->add('slug');
        $builder->add('body');
        $builder->add('metaDescription');
        $builder->add('publishedAt');
        $builder->add('isActive', 'checkbox', array('required' => false));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Alom\WebsiteBundle\Entity\Post'
        );
    }

    public function getName()
    {
        return 'alom_website_post';
    }
}
