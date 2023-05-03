<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
    }

    /**
     * @param string $codePromo
     * @return Promotion|null
     */
    public function findByCodePromo(string $codePromo): ?Promotion
    {
        return $this->findOneBy(['codePromo' => $codePromo]);
    }

    /**
     * @param float $taux
     * @return Promotion[] Returns an array of Promotion objects
     */
    public function findByTaux(float $taux): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.taux = :taux')
            ->setParameter('taux', $taux)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $userId
     * @return Promotion[] Returns an array of Promotion objects
     */
    public function findByUserId(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idUser = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
