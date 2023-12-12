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

    public function getDisplayedRecruitments(?string $field, ?string $sort): array
    {
        if (!$field) {
            $field = 'id';
        }

        if (!$sort) {
            $sort = 'ASC';
        }

        try {
            return $this->createQueryBuilder('r')
                ->where('r.displayed LIKE :query')
                ->setParameter('query',  true)
                ->orderBy('r.'.$field, $sort)
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getNonDisplayedRecruitments(?string $field, ?string $sort): array
    {
        if (!$field) {
            $field = 'id';
        }

        if (!$sort) {
            $sort = 'ASC';
        }

        try {
            return $this->createQueryBuilder('r')
                ->where('r.displayed LIKE :query')
                ->setParameter('query',  false)
                ->orderBy('r.'.$field, $sort)
                ->getQuery()
                ->getResult();
        } catch (\Exception $e) {
            return [];
        }
    }
}
