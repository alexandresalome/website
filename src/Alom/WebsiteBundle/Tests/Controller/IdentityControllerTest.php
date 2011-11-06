<?php
namespace Alom\Website\ContentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IdentityControllerTest extends WebTestCase
{
    public function testCv()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/cv');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check document structure
        $this->assertEquals($crawler->filter('title')->text(), 'CV | Alexandre Salomé');

        $content = $client->getResponse()->getContent();
        $keywords = array('Sensio Labs', 'Wokine', 'Symfony2', 'Automation',
            'Git', 'Linux', 'Vim', 'XHTML', 'Ergonomy', 'Photoshop', 'Flash',
            'GMI', 'MIMP', 'Admoove', 'Only Talent Productions');
        foreach ($keywords as $keyword) {
            $this->assertRegExp('/' . preg_quote($keyword) . '/', $content);
        }
    }

    public function testContact()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/contact');

        // Check the response object
        $response = $client->getResponse();
        $this->assertEquals($response->getStatusCode(), 200);

        // Check document structure
        $this->assertEquals($crawler->filter('title')->text(), 'Contact | Alexandre Salomé');

        $this->assertEquals($crawler->filter('#content h1')->count(), 1);
        $this->assertEquals($crawler->filter('#content h1')->text(), 'Contact');

        $this->assertRegExp('/alexandre\.salome@gmail\.com/', $client->getResponse()->getContent());
    }
}
