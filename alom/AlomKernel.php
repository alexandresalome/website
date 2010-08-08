<?php

require_once __DIR__ . '/../src/autoload.php';

use Symfony\Framework\Kernel;
use Symfony\Components\DependencyInjection\Loader\YamlFileLoader as ContainerLoader;
use Symfony\Components\Routing\Loader\YamlFileLoader as RoutingLoader;
use Symfony\Components\DependencyInjection\Loader\LoaderInterface;
use Symfony\Framework\KernelBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\ZendBundle\ZendBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\DoctrineMigrationsBundle\DoctrineMigrationsBundle;
use Application\Alom\PageBundle\PageBundle;
use Application\Alom\IdentityBundle\IdentityBundle;
use Bundle\Alom\BlogBundle\BlogBundle;
use Bundle\Alom\ContactBundle\ContactBundle;

/**
 * Alom Kernel
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class AlomKernel extends Kernel
{
    /**
     * @inherited
     */
    public function registerRootDir() {
        return __DIR__;
    }

    /**
     * @inherited
     */
    public function registerBundles() {
        $bundles = array(
            new KernelBundle(),
            new FrameworkBundle(),
            new ZendBundle(),
            new SwiftmailerBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new PageBundle(),
            new IdentityBundle(),
            new BlogBundle(),
            new ContactBundle()
        );

        if ($this->isDebug()) {
        }

        return $bundles;
    }

    /**
     * @inherited
     */
    public function registerBundleDirs() {
        return array(
            'Application\\Alom' => __DIR__ . '/../src/Application/Alom',
            'Bundle\\Alom'      => __DIR__ . '/../src/Bundle/Alom',

            'Application'       => __DIR__ . '/../src/Application',
            'Bundle'            => __DIR__ . '/../src/Bundle',
            'Symfony\\Bundle'   => __DIR__ . '/../src/vendor/symfony/src/Symfony/Bundle'
        );
    }

    /**
     * @inherited
     */
    public function registerContainerConfiguration(LoaderInterface $loader) {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * @inherited
     */
    public function registerRoutes() {
        $loader = new RoutingLoader($this->getBundleDirs());

        return $loader->load(__DIR__ . '/config/routing.yml');
    }
}
