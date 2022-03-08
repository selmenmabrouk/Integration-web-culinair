<?php

namespace App\Repository;

use App\Entity\ActiviteLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActiviteLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActiviteLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActiviteLike[]    findAll()
 * @method ActiviteLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActiviteLike::class);
    }

    public function add(ActiviteLike $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(ActiviteLike $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
