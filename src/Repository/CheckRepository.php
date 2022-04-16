<?php

namespace App\Repository;

use App\Entity\Check;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Check|null find($id, $lockMode = null, $lockVersion = null)
 * @method Check|null findOneBy(array $criteria, array $orderBy = null)
 * @method Check[]    findAll()
 * @method Check[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheckRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Check::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Check $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Check $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getMaxOptionPosition(Check $entity) : int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT MAX(o.position)
            FROM App\Entity\Option o
            WHERE o.parent = :parent
            '
        )->setParameter('parent', $entity->getId());

        return $query->getSingleScalarResult() ?? 0;
    }


}
