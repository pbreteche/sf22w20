<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
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

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCategoryName(string $name)
    {
        return $this->createQueryBuilder('post')
            // Fetch join: permet de charger la catégorie si la relation est configurée en LAZY
            ->addSelect('category')
            ->innerJoin('post.categorizedBy', 'category')
            ->andWhere('category.name = :name')
            ->getQuery()
            ->setParameter('name', $name)
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findByMonth(\DateTimeImmutable $month, bool $ordered = false): array
    {
        $qb = $this->createQueryBuilder('post'); // clause SELECT et FROM
        $qb
            ->andWhere('post.createdAt >= :from') // clause WHERE
            ->andWhere('post.createdAt < :to')
        ;
        if ($ordered) {
            $qb->orderBy('post.createdAt', 'DESC');
        }

        return $qb
            ->getQuery()
            ->setParameters([
                'from' => $month->modify('first day of this month midnight'),
                'to' => $month->modify('first day of next month midnight'),
            ])
            ->getResult()
        ;
    }

    /**
     * @return Post[]
     */
    public function findByMonthDQL(\DateTimeImmutable $month): array
    {
        return $this
            ->getEntityManager()
            ->createQuery(
                'SELECT post FROM '.Post::class.' AS post '.
                'WHERE post.createdAt >= :from '.
                'AND post.createdAt < :to '.
                'ORDER BY post.createdAt DESC'
            )
            ->setParameters([
                'from' => $month->modify('first day of this month midnight'),
                'to' => $month->modify('first day of next month midnight'),
            ])
            ->getResult()
        ;
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
