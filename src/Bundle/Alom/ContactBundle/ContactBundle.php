<?php

namespace Bundle\Alom\ContactBundle;

use Symfony\Framework\Bundle\Bundle;
use Symfony\Components\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Components\DependencyInjection\ContainerBuilder;
use Symfony\Components\DependencyInjection\Loader\Loader;

use Bundle\Alom\ContactBundle\DependencyInjection\ContactExtension;

class ContactBundle extends Bundle
{
    public function buildContainer(ParameterBagInterface $parameterBag)
    {
        ContainerBuilder::registerExtension(new ContactExtension());
    }
}
