<?php

namespace Alom\WebsiteBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\Container;

use Alom\WebsiteBundle\Twig\Extension\ProjectExtension;

class ProjectExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $extension;

    public function setUp()
    {
        $container = new Container();
        $this->extension = new ProjectExtension($container);
    }

    /**
     * @dataProvider provideFormatTextUrl
     */
    public function testFormatTextUrl($url)
    {
        $text = 'I am on '.$url.' and I enjoy it';
        $url = str_replace('&', '&amp;', $url);
        $this->assertEquals('<p>I am on <a href="'.$url.'">'.$url.'</a> and I enjoy it</p>', $this->extension->formatText($text));
    }

    public function testFormatTextEscaping()
    {
        $text = 'Simple text to format';
        $this->assertEquals('<p>'.$text.'</p>', $this->extension->formatText($text));

        $text = 'Text with <em>HTML</em>';
        $this->assertEquals('<p>Text with &lt;em&gt;HTML&lt;/em&gt;</p>', $this->extension->formatText($text));

        $text = 'Text with a french mot-clé';
        $this->assertEquals('<p>Text with a french mot-clé</p>', $this->extension->formatText($text));

        $text = 'Text with a & ampersand';
        $this->assertEquals('<p>Text with a &amp; ampersand</p>', $this->extension->formatText($text));

        $text = 'Text with'."\n".'new line';
        $this->assertEquals('<p>Text with<br />new line</p>', $this->extension->formatText($text));
    }

    public function provideFormatTextUrl()
    {
        return array(
            array('http://alexandre-salome.fr'),
            array('http://www.alexandre-salome.fr'),
            array('http://www.alexandre-salome.fr#chapter'),
            array('http://www.alexandre-salome.fr/index.php&x=1'),
        );
    }
}
