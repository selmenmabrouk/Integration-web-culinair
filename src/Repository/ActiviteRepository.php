<?php

namespace App\Repository;

use App\Entity\Activite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activite::class);
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(): Query
    {
        return $this->findAllVisibleQuery()
            ->getQuery();
    }

    public function findByNamePopular(string $activite)
    {
        $queryBuilder = $this->createQueryBuilder('activite')
            ->where('activite.nom LIKE :searchTerm')
            ->orWhere('activite.description LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $activite . '%');

        return $queryBuilder
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
