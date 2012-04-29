<?php
namespace Alom\WebsiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;
Use Doctrine\ORM\Query;

class PostCommentRepository extends EntityRepository
{
    public function fetchAllOrderedByDate()
    {
        $qb = $this->createQueryBuilder('pc');
        $qb->leftJoin('pc.post', 'p');

        $qb->addOrderBy('pc.createdAt', 'DESC');

        $data = $qb->getQuery()->execute();

        return $data;
    }
}
