<?php

namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agent>
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }


public function countByPlayer(User $user): int
{
    return (int) $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->where('a.player = :u')
        ->setParameter('u', $user)
        ->getQuery()
        ->getSingleScalarResult();
}

public function countDistinctGamesByPlayer(User $user): int
{
    return (int) $this->createQueryBuilder('a')
        ->select('COUNT(DISTINCT a.game)')
        ->where('a.player = :u')
        ->setParameter('u', $user)
        ->getQuery()
        ->getSingleScalarResult();
}
    public function searchAndSort(?string $q, ?string $sort, ?string $dir): array
    {
        $qb = $this->createQueryBuilder('a');
        if ($q) {
        
            $qb->andWhere('a.pseudo LIKE :q OR a.game LIKE :q OR a.rank LIKE :q')
               ->setParameter('q', '%' . $q . '%');
        }
        $allowedSort = [
            'id' => 'a.id',
            'pseudo' => 'a.pseudo', 
            'game' => 'a.game',
            'rank' => 'a.rank',
            'status' => 'a.status'
        ];
        $sortField = $allowedSort[$sort] ?? 'a.id';

        $direction = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';

        $qb->orderBy($sortField, $direction);

        return $qb->getQuery()->getResult();
    }
}