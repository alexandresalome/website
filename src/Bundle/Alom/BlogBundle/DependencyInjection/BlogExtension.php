<?php

namespace Bundle\Alom\BlogBundle\DependencyInjection;

use Symfony\Components\DependencyInjection\Extension\Extension;
use Symfony\Components\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Components\DependencyInjection\ContainerBuilder;
use Symfony\Components\DependencyInjection\Reference;

/**
 * BlogExtension add helpers to dependency injection
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BlogExtension extends Extension
{
    protected $resources = array(
        'blog' => 'blog.xml',
    );

    /**
     * Load the blog helpers
     */
    public function helperLoad($config, ContainerBuilder $container) {
        if (!$container->hasDefinition('blog.helper')) {
            $loader = new XmlFileLoader($container, __DIR__ . '/../Resources/config');
            $loader->load($this->resources['blog']);
        }
    }

    /**
     * @inherited
     */
    public function getXsdValidationBasePath() {
        return __DIR__ . '/../Resources/config/schema';
    }

    /**
     * @inherited
     *
     * @todo Publier le DIC
     */
    public function getNamespace() {
        return null;
    }

    /**
     * @inherited
     */
    public function getAlias() {
        return 'blog';
    }
}
