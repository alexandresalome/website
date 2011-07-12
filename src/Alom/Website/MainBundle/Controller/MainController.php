<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Main controller
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class MainController extends Controller
{
    /**
     * Homepage
     *
     * @return Response
     */
    public function homepageAction()
    {
        return $this->render('AlomMainBundle:Main:homepage.html.twig');
    }

    public function sitemapAction()
    {
        $response = $this->render('AlomMainBundle:Main:sitemap.xml.twig', array(
            'posts' => $this->get('doctrine.orm.default_entity_manager')->getRepository('AlomBlogBundle:Post')->fetchAllOrderedByDate()
        ));

        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
