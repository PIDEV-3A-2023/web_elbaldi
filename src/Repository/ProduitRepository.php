<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($ref_produit, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByCategorie($cat)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Produit s
            JOIN s.categorie c
            WHERE c.id_categorie = :cat
            ORDER BY s.libelle ASC'
        )->setParameter('cat', $cat);

        return $query->getResult();
    }

    public function countByCategory(): array
{
    $qb = $this->createQueryBuilder('p')
        ->select('c.nom_categorie', 'COUNT(p.ref_produit) as product_count')
        ->join('p.categoriee', 'c')
        ->groupBy('c.nom_categorie');
        
    return $qb->getQuery()->getResult();
}


    public function getStat(): array
    {
            return $this->createQueryBuilder('produit')
            ->select('count(produit.ref_produit) as nbre,categorie.nomCategorie')
            ->leftJoin('produit.categorie', 'categorie')
            ->groupBy("produit.categorie")
            ->getQuery()
            ->execute();

    }

public function findByPrixVenteAsc():array
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.prixVente', 'ASC')
        ->getQuery()
        ->getResult();
}

public function findByPrixVenteDesc():array
{
    return $this->createQueryBuilder('p')
        ->orderBy('p.prixVente', 'DESC')
        ->getQuery()
        ->getResult();
}

public function rechercherParLibelle(string $libelle): array
{
    $qb = $this->createQueryBuilder('p');
    $qb->where($qb->expr()->like('p.libelle', ':libelle'))
       ->setParameter('libelle', '%' . $libelle . '%');
    return $qb->getQuery()->getResult();
}

public function countProducts(): int
{
    $entityManager = $this->getEntityManager();
    $query = $entityManager->createQuery('SELECT COUNT(p.ref_produit) FROM App\Entity\Produit p');
    return $query->getSingleScalarResult();
}

public function findByTelByCategoryId($categoryId) {
    $query = $this->getEntityManager()->createQuery(
        'SELECT DISTINCT u.numtel, u.nom, u.prenom 
        FROM App\Entity\Utilisateur u
        JOIN App\Entity\Panier p WITH u.idUser = p.idUser 
        JOIN App\Entity\Commande c WITH p.idPanier = c.idPanier 
        JOIN App\Entity\CommandProduit cp WITH c.idCmd = cp.idCmd 
        WHERE cp.ref_produit IN (
            SELECT pr.ref_produit
            FROM App\Entity\Produit pr
            JOIN pr.categorie cat
            WHERE cat.id_categorie = :categoryId
        ) AND u.numtel IS NOT NULL'
    )->setParameter('categoryId', $categoryId);

    $results = $query->getResult();
     // Process result set to extract phone numbers as strings
     $phoneNumbers = array_map(function($row) {
        return strval($row['numtel']);
    }, $results);

    foreach ($results as $result) {
        $numtel = $result['numtel'];
        $nom = $result['nom'];
        $prenom = $result['prenom'];
        $numtels[] = [
            'numtel' => $numtel,
            'nom' => $nom,
            'prenom' => $prenom
        ];

    }
    return $phoneNumbers;
}

public function findByEmailsByCategoryId($categoryId) {
    $query = $this->getEntityManager()->createQuery(
        'SELECT DISTINCT u.email, u.nom, u.prenom 
        FROM App\Entity\Utilisateur u
        JOIN App\Entity\Panier p WITH u.idUser = p.idUser 
        JOIN App\Entity\Commande c WITH p.idPanier = c.idPanier 
        JOIN App\Entity\CommandProduit cp WITH c.idCmd = cp.idCmd 
        WHERE cp.ref_produit IN (
            SELECT pr.ref_produit
            FROM App\Entity\Produit pr
            JOIN pr.categorie cat
            WHERE cat.id_categorie = :categoryId
        ) AND u.email IS NOT NULL'
    )->setParameter('categoryId', $categoryId);

    $results = $query->getResult();
    $emails = [];

    foreach ($results as $result) {
        $email = $result['email'];
        $nom = $result['nom'];
        $prenom = $result['prenom'];
        $emails[] = [
            'email' => $email,
            'nom' => $nom,
            'prenom' => $prenom
        ];
    }

    return $emails;
}






//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.ref_produit', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
