<?php
namespace Alom\Website\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class PostRepository extends EntityRepository
{
    public function findOneBySlugWithRelated($slug)
    {
        $query = $this->createQueryBuilder('p')
            ->select(array('p', 'pc'))
            ->where('p.slug = :slug')
            ->leftJoin('p.comments', 'pc')
            ->setParameter('slug', $slug)
            ->getQuery();

        try {
            $post = $query->getSingleResult();
            $this->addPreviousAndNext($post);
            return $post;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    protected function addPreviousAndNext(Post $post)
    {
        $next = $this
            ->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'ASC')
            ->where('p.publishedAt > :publication')
            ->setParameter('publication', $post->getPublishedAt()->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        $post->setNext(count($next) ? $next[0] : false);

        $previous = $this
            ->createQueryBuilder('p')
            ->orderBy('p.publishedAt', 'DESC')
            ->where('p.publishedAt < :publication')
            ->setParameter('publication', $post->getPublishedAt()->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        $post->setPrevious(count($previous) ? $previous[0] : false);
    }

    public function fetchAllOrderedByDate()
    {
        return $this
            ->createQueryBuilder('p')
            ->addOrderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->execute();
    }
}