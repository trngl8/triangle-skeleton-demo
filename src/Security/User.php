<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $username;

    private $password;

    private $name;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }


}
