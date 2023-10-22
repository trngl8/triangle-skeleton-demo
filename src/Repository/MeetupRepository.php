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
        return $this->find($id);
    }

}
