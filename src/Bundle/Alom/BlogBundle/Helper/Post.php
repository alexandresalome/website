<?php
namespace Bundle\Alom\BlogBundle\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Helper for blog post model
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Post
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Instanciate the helper with the entity manager.
     *
     * @param EntityManager $entityManager Entity manager to fetch/persist on
     */
    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * Get a list of posts
     *
     * @param Integer $year Year to fetch
     *
     * @return Array A collection of posts
     */
    public function getList($year = null) {
        $qBuilder = $this
            ->entityManager
            ->getRepository('BlogBundle:Post')
            ->createQueryBuilder('post')
            ->select('post')
        ;

        if (null !== $year) {
            $qBuilder
                ->where('post.publishedAt LIKE :year')
                ->setParameter('year', $year."%")
            ;
        }

        $qBuilder
            ->orderBy("post.publishedAt", "DESC")
        ;

        return $qBuilder->getQuery()->getResult();
        ;
    }

    /**
     * Get a post by slug
     *
     * @param string $slug 
     */
    public function find($slug) {
        return $this
            ->entityManager
            ->getRepository('BlogBundle:Post')
            ->findOneBy(array('slug' => $slug));
    }
}
