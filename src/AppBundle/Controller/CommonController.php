<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommonController extends Controller
{
    public function bannerAction()
    {
        $user = $this->getUser();
        $isConnected = $this->isGranted('IS_AUTHENTICATED_FULLY');
        $username = $isConnected ? $user->getUsername() : null;

        return $this->render('::Common/banner.html.twig', array(
            'is_connected' => $isConnected,
            'username'     => $username
        ));
    }
}
