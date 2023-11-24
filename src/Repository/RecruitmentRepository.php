<?php

namespace App\Repository;

use App\Entity\Recruitment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recruitment>
 *
 * @method Recruitment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recruitment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recruitment[]    findAll()
 * @method Recruitment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruitmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruitment::class);
    }

    public function searchDisplayedRecruitments(bool $displayed, string $orderBy = 'id', string $sortBy = 'ASC') :array
    {
        return $this->createQueryBuilder('r')
            ->where('r.displayed LIKE :query')
            ->setParameter('query',  $displayed)
            ->orderBy('r.'.$orderBy, $sortBy)
            ->getQuery()
            ->getResult();
    }
}
