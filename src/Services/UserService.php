<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $em;
    private $passwordHasher;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $doctrine->getManager();
        $this->passwordHasher = $passwordHasher;
    }

    public function create(string $username, string $password) : User
    {
        $user = (new User())
            ->setUsername($username)
        ;

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->em->persist($user);

        return $user;
    }

    public function flush() : void
    {
        $this->em->flush();
    }

    public function findBy(array $criteria) : array
    {
        return $this->em->getRepository(User::class)->findBy($criteria);
    }
}
