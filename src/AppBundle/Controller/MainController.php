<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homepageAction()
    {
        $lastPosts = $this->getDoctrine()->getRepository('AppBundle:Post')->fetchLast(5);
        $lastBooks = $this->getDoctrine()->getRepository('AppBundle:Book')->fetchLast(5);

        return $this->render('::Main/homepage.html.twig', array(
            'lastPosts' => $lastPosts,
            'lastBooks'  => $lastBooks
        ));
    }

    public function sitemapAction()
    {
        $response = $this->render('::Main/sitemap.xml.twig', array(
            'posts' => $this->getDoctrine()->getRepository('AppBundle:Post')->fetchAllOrderedByDate()
        ));

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
