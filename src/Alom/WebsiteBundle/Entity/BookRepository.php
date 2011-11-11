<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Alom\WebsiteBundle\Entity;

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

    public function fetchLast($count = 5)
    {
        return $this
            ->createQueryBuilder('b')
            ->select('b.title, b.slug, b.illustration')
            ->where('b.isActive = true')
            ->orderBy('b.readAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->execute()
        ;
    }
}
