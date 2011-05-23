<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Website\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Alom\Website\BlogBundle\Entity\PostComment;
use Alom\Website\BlogBundle\Form\PostComment as PostCommentForm;

class PostCommentController extends Controller
{
    public function activateAction($slug)
    {
    }

    public function inactivateAction($slug)
    {
    }
}
