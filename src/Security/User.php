<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $username;

    private $password;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
