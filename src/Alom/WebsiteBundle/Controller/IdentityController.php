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
 * Identity controller.
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class IdentityController extends Controller
{
    /**
     * CV : Static page
     */
    public function cvAction()
    {
        return $this->render('AlomWebsiteBundle:Identity:cv.html.twig');
    }

    /**
     * Contact : Static page
     */
    public function contactAction()
    {
        return $this->render('AlomWebsiteBundle:Identity:contact.html.twig');
    }
}
