<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\WebsiteBundle\Controller;

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
        $lastPosts = $this->getDoctrine()->getRepository('AlomWebsiteBundle:Post')->fetchLast(5);
        $lastBooks = $this->getDoctrine()->getRepository('AlomWebsiteBundle:Book')->fetchLast(5);

        return $this->render('AlomWebsiteBundle:Main:homepage.html.twig', array(
            'lastPosts' => $lastPosts,
            'lastBooks'  => $lastBooks
        ));
    }

    public function sitemapAction()
    {
        $response = $this->render('AlomWebsiteBundle:Main:sitemap.xml.twig', array(
            'posts' => $this->getDoctrine()->getRepository('AlomWebsiteBundle:Post')->fetchAllOrderedByDate()
        ));

        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}
