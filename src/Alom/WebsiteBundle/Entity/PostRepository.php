<?php
namespace Alom\WebsiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;

class PostRepository extends EntityRepository
{
    public function fetchLast($count = 5)
    {
        return $this
            ->createQueryBuilder('p')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->where('p.isActive = true')
            ->setMaxResults(5)
            ->getQuery()
            ->execute()
        ;
    }

    public function findOneBySlugWithRelated($slug, $fetchInactive = false)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select(array('p', 'pc'))
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
        ;

        if (false === $fetchInactive) {
            $query
                ->andWhere('p.isActive = true')
                ->leftJoin('p.comments', 'pc', Expr\Join::WITH, 'pc.isModerated = true')
            ;
        } else {
            $query
                ->leftJoin('p.comments', 'pc')
            ;
        }

        try {
            $post = $query->getQuery()->getSingleResult();
            $this->addPreviousAndNext($post, $fetchInactive);
            return $post;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    protected function addPreviousAndNext(Post $post, $fetchInactive = false)
    {
        $activeCondition = $fetchInactive ? '' : ' AND p.isActive = true';
        $next = $this
            ->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'ASC')
            ->where('p.publishedAt > :publication' . $activeCondition)
            ->setParameter('publication', $post->getPublishedAt()->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        $post->setNext(count($next) ? $next[0] : false);

        $previous = $this
            ->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'DESC')
            ->where('p.publishedAt < :publication' . $activeCondition)
            ->setParameter('publication', $post->getPublishedAt()->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        $post->setPrevious(count($previous) ? $previous[0] : false);
    }

    public function fetchAllOrderedByDate($fetchInactive = false)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->addOrderBy('p.publishedAt', 'DESC');

        if (false === $fetchInactive) {
            $query->andWhere('p.isActive = true');
        }

        return $query->getQuery()->execute();
    }
}