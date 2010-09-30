<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Alom\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class MainController extends Controller
{
    /**
     * CV
     */
    public function homepageAction() {
        return $this->render('PageBundle:Main:homepage.php');
    }

    /**
     * 404 Error Page
     */
    public function error404Action() {
        $response = $this->render('PageBundle:Main:error404.php');
        $response->setStatusCode(404); // Not found

        return $response;
    }
}
