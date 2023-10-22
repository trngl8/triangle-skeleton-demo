<?php

namespace App\Repository;

use App\Entity\Meetup;
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

    public function get(int $id): array
    {
        return ['id' => $id, 'title' => sprintf('Meetup %d', $id), 'plannedAt' => new \DateTimeImmutable()];
    }

//    public function findAll(): iterable
//    {
//        return [
//            ['id' => 1, 'title' => 'Meetup 1', 'plannedAt' => new \DateTimeImmutable()],
//            ['id' => 2, 'title' => 'Meetup 2', 'plannedAt' => new \DateTimeImmutable()],
//            ['id' => 3, 'title' => 'Meetup 3', 'plannedAt' => new \DateTimeImmutable()],
//        ];
//    }
}
