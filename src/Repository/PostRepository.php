<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     *
     */
    public function search($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.title LIKE :val or p.shortDescription LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('p.id', 'DESC')        
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    public function searchAndFilterByTags($search)
    {
        $query= $this->createQueryBuilder('p')
                     ->select('t', 'p')
                     ->join('p.tags', 't');

            if (!empty($search->tags)){
                $query= $query
                ->andWhere('t IN (:tags)')
                ->setParameter('tags', $search->tags);
            }

            if (!empty($search->string)){
                $query= $query
                ->andWhere('p.title LIKE :val or p.shortDescription LIKE :val')
                ->setParameter('val', '%'.$search->string.'%');
            }

            return $query->getQuery()->getResult();
    }
    
}
