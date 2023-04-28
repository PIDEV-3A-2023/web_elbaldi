<?php

namespace App\Repository;

use App\Entity\Bonplan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Bonplan>
 *
 * @method Bonplan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bonplan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bonplan[]    findAll()
 * @method Bonplan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BonplanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonplan::class);
    }

    public function save(Bonplan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Bonplan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBonplan($name)
    {
        return $this->createQueryBuilder('bonplan')
                    ->where('bonplan.titreBonplan LIKE :nom OR bonplan.descriptionBonplan LIKE :nom OR bonplan.typeBonplan LIKE :nom')
                    ->setParameter('nom','%'.$name.'%')
                    ->getQuery()
                    ->getResult();
    }

    // public function finAverage(int $idBonplan)
    // {
    //     $qb = $this->createQueryBuilder('bp')
    //         ->select('bp.id_bonplan', 'AVG(a.note_avis) AS avg_note_avis')
    //         ->leftJoin('bp.avis', 'a')
    //         ->where('bp.id_bonplan = :idBonplan')
    //         ->setParameter('idBonplan', $idBonplan)
    //         ->groupBy('bp.id_bonplan');

    //     return $qb->getQuery()->getResult();
    // }


    // public function findAverageNoteAvis()
    // {
    //     $qb = $this->createQueryBuilder('bp')
    //         ->select('bp.id_bonplan', 'AVG(a.note_avis) AS avg_note_avis')
    //         ->leftJoin('bp.avis', 'a')
    //         ->groupBy('bp.id_bonplan');

    //     return $qb->getQuery()->getResult();
    // }


    public function findAverageNoteAvisByIdBonplan($idBonplan)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b', 'AVG(a.noteAvis) as averageNote')
            ->leftJoin('b.avis', 'a')
            ->where('b.idBonplan = :idBonplan')
            ->setParameter('idBonplan', $idBonplan)
            ->groupBy('b');
    
        return $qb->getQuery()->getOneOrNullResult();
    }
    

//    /**
//     * @return Bonplan[] Returns an array of Bonplan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Bonplan
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
