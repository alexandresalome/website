<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Alom\Website\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    public function getList($showInactive = false)
    {
        $query = $this->createQueryBuilder('b');

        if (false === $showInactive) {
            $query->where('b.isActive = true');
        }

        return $query
            ->orderBy('b.readAt', 'DESC')
            ->getQuery()
            ->execute()
        ;
    }
}
