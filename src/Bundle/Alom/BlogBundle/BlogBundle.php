<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Bundle\Alom\BlogBundle;

use Symfony\Framework\Bundle\Bundle;
use Symfony\Components\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Components\DependencyInjection\ContainerBuilder;
use Symfony\Components\DependencyInjection\Loader\Loader;

use Bundle\Alom\BlogBundle\DependencyInjection\BlogExtension;

class BlogBundle extends Bundle
{
    public function buildContainer(ParameterBagInterface $parameterBag)
    {
        ContainerBuilder::registerExtension(new BlogExtension());
    }
}
