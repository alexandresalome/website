<?php

namespace Alom\WebsiteBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjectExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getGlobals()
    {
        return array(
            'rssAddress' => $this->container->getParameter('rss_address'),
            'rssTitle'   => $this->container->getParameter('rss_title')
        );
    }
    public function getName()
    {
        return 'alom_project';
    }
}
