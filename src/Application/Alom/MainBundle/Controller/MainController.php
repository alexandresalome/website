<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Alom\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Main controller
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class MainController extends Controller
{
    /**
     * CV
     */
    public function homepageAction() {
        return $this->render('MainBundle:Main:Homepage.php');
    }
}
