<?php

namespace App\Repository;

use App\Entity\TimeData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<TimeData>
 *
 * @method TimeData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeData[]    findAll()
 * @method TimeData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeData::class);
    }

    public function save(TimeData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TimeData $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get(string $uuid): ?array
    {
        $items = array_filter($this->findAllDays(), fn (array $entity) => $entity['uuid'] === $uuid);
        return array_pop($items);
    }

    public function findToday(): array
    {
        return [
            [
                'id' => 1,
                'uuid' => '5b1681a3-9fa1-43a8-ab52-87ca5c73b0b1',
                'title' => 'Intro',
                'type' => 'primary',
                'startAt' => '2023-06-20 19:00',
                'time' => '19:00'
            ],
            [
                'id' => 2,
                'uuid' => '8ef73490-33ac-4f72-8100-f454afd94ee6',
                'title' => 'Interview',
                'type' => 'danger',
                'startAt' => '2023-06-20 14:00',
                'time' => '14:00'
            ]
        ];
    }

    public function findTomorrow(): array
    {
        return [
            [
                'id' => 3,
                'uuid' => 'd2a9e67a-c5d8-43d4-af95-56cd7e948f5e',
                'title' => 'Develop',
                'startAt' => '2023-06-20 19:00',
                'time' => '19:00'
            ],
        ];
    }

    public function findAllDays(): array
    {
        return array_merge($this->findToday(), $this->findTomorrow());
    }

}
