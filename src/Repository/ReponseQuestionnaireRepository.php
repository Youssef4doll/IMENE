<?php

namespace App\Repository;

use App\Entity\ReponseQuestionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseQuestionnaire>
 */
class ReponseQuestionnaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseQuestionnaire::class);
    }

    /**
     * For "Questionnaire Validé" badge per agent in the list
     */
    public function findAnsweredAgentIds(): array
    {
        $rows = $this->createQueryBuilder('r')
            ->select('DISTINCT a.id AS agentId')
            ->join('r.agent', 'a')
            ->getQuery()
            ->getScalarResult();

        return array_map(static fn($row) => (int) $row['agentId'], $rows);
    }

    public function countDistinctGamesAnswered(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(DISTINCT q.game)')
            ->join('r.questionnaire', 'q')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countNoSkips(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.rep3 IS NOT NULL')
            ->andWhere('r.rep4 IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
