<?php

namespace Alom\Website\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function fetchAllOrderedByDate()
    {
        return $this
            ->createQueryBuilder('p')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->execute();
        ;
        
    }
}