<?php

namespace Bundle\Alom\ContactBundle;

use Symfony\Framework\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Loader;

use Bundle\Alom\ContactBundle\DependencyInjection\ContactExtension;

class ContactBundle extends Bundle
{
    public function buildContainer(ParameterBagInterface $parameterBag)
    {
        ContainerBuilder::registerExtension(new ContactExtension());
    }
}
