<?php

namespace App\Repository;

use App\Entity\QuestionnaireAgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionnaireAgent>
 */
class QuestionnaireAgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionnaireAgent::class);
    }

    public function searchAndSort(?string $q, ?string $sort, ?string $dir): array
    {
        $qb = $this->createQueryBuilder('qa');

        if ($q) {
            $qb->andWhere('qa.game LIKE :q')
               ->setParameter('q', '%'.$q.'%');
        }

        $allowed = [
            'game' => 'qa.game',
            'ques1' => 'qa.ques1',
            'ques2' => 'qa.ques2',
            'ques3' => 'qa.ques3',
            'ques4' => 'qa.ques4',
        ];

        $sortField = $allowed[$sort] ?? 'qa.game';
        $direction = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';

        $qb->orderBy($sortField, $direction);

        return $qb->getQuery()->getResult();
    }

    public function findDistinctGames(): array
    {
        return $this->createQueryBuilder('q')
            ->select('DISTINCT q.game')
            ->orderBy('q.game', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }
}