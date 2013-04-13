<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\PostComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullname', TextType::class, array(
            'label' => 'Fullname *'
        ));
        $builder->add('email', EmailType::class, array(
            'label' => 'Email *'
        ));
        $builder->add('website', UrlType::class, array(
            'label'    => 'Website',
            'required' => false
        ));
        $builder->add('body', TextareaType::class, array(
            'label' => 'Comment *'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PostComment::class
        ));
    }
}
