<?php
namespace Alom\WebsiteBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;
Use Doctrine\ORM\Query;

class PostRepository extends EntityRepository
{
    public function fetchLast($count = 5)
    {
        return $this
            ->createQueryBuilder('p')
            ->select('p.title, p.metaDescription, p.slug')
            ->where('p.isActive = true')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->setMaxResults($count)
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

    public function fetchAllOrderedByDate($fetchInactive = false, $summary = true)
    {
        $qb = $this->createQueryBuilder('p');

        if (true === $summary) {
            $qb->select('p.title, p.publishedAt, p.slug, p.isActive');
        }

        $qb->addOrderBy('p.publishedAt', 'DESC');

        if (false === $fetchInactive) {
            $qb->andWhere('p.isActive = true');
        }

        $data = $qb->getQuery()->execute();

        if (true === $summary) {
            foreach (array_keys($data) as $i) {
                $data[$i]['publishedAt'] = new \DateTime($data[$i]['publishedAt']);
            }
        }

        return $data;
    }
}
