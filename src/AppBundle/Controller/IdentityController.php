<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IdentityController extends Controller
{
    public function cvAction()
    {
        return $this->render('::Identity/cv.html.twig');
    }

    public function contactAction()
    {
        return $this->render('::Identity/contact.html.twig');
    }
}
