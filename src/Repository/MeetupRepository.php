<?php

namespace App\Repository;

use App\Entity\Meetup;
use App\Entity\Subscribe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MeetupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meetup::class);
    }

    public function add(Meetup $entity): void
    {
        $this->getEntityManager()->persist($entity);
    }

    public function save(): void
    {
        $this->getEntityManager()->flush();
    }

    public function get(int $id): ?Meetup
    {
        return $this->find($id);
    }

    public function remove($entity): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function findCurrent(): iterable
    {
        $meetings = $this->createQueryBuilder('m')
            ->orderBy('m.plannedAt', 'ASC')
            ->getQuery()
            ->getArrayResult();

        // TODO: use join
        foreach ($meetings as &$meeting) {
            $meeting['subscribers'] = $this->getEntityManager()->getRepository(Subscribe::class)->findBy([
                'type' => 'meetup',
                'target' => $meeting['id']
            ]);
        }

        return $meetings;
    }

}
