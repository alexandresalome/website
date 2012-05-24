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

    public function getFilters()
    {
        return array(
            'format_text' => new \Twig_Filter_Method($this, 'formatText', array('is_safe' => array('html')))
        );
    }

    public function formatText($text)
    {
        $text = str_replace("\n", "<br />", htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        $urlPattern = '#https?\://\S+#';

        return '<p>'.preg_replace($urlPattern, '<a href="$0">$0</a>', $text).'</p>';
    }

    public function getName()
    {
        return 'alom_project';
    }
}
