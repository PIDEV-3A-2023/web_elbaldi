<?php

namespace App\Repository;

use App\Entity\CommandProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommandProduit>
 *
 * @method CommandProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandProduit[]    findAll()
 * @method CommandProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandProduit::class);
    }

    public function save(CommandProduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CommandProduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

public function top5prod() :array
{
    $qb = $this->createQueryBuilder('cp')
        ->select('cp.ref_produit, COUNT(cp.id) AS total')
        ->where('cp.date_cmd >= :startOfMonth')
        ->setParameter('startOfMonth', new \DateTime('first day of this month'))
        ->groupBy('cp.ref_produit')
        ->orderBy('total', 'DESC')
        ->setMaxResults(5);

    return $qb->getQuery()->getResult();
}
public function findFiveLeastSoldProducts():array
{
    $qb = $this->createQueryBuilder('cp')
        ->select('cp.ref_produit, COUNT(cp.id) AS total')
        ->where('cp.date_cmd >= :startOfMonth')
        ->setParameter('startOfMonth', new \DateTime('first day of this month'))
        ->groupBy('cp.ref_produit')
        ->orderBy('total', 'ASC')
        ->setMaxResults(5);

    return $qb->getQuery()->getResult();
}

//    /**
//     * @return CommandProduit[] Returns an array of CommandProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CommandProduit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
