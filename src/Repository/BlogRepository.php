<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @param $phrase string
     * @param $page
     * @return mixed
     */
    public function getBySearch($phrase, $page){
        $qb = $this->createQueryBuilder("b");
        return $qb
            ->where("b.title LIKE :phrase")
            ->orWhere( "b.text LIKE :phrase")
            ->leftJoin("b.user", "u")
            ->orWhere( "u.username LIKE :phrase")
            ->setParameter("phrase", "%".$phrase."%")
            ->setFirstResult(($page - 1) * 20)
            ->setMaxResults(20)
            ->orderBy("b.created_at")
            ->getQuery()
            ->getResult();
    }
}
