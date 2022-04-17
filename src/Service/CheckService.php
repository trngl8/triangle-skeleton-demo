<?php

namespace App\Service;

use App\Entity\Option;
use App\Entity\Result;
use Doctrine\Persistence\ManagerRegistry;

class CheckService
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function createCheckResult($user, int $optionId) : Result
    {
        /** @var Option $option */
        $option = $this->doctrine->getRepository(Option::class)->find($optionId);

        if(!$option) {
            throw new \Exception(sprintf('Option %d not found', $optionId));
        }

        $result = new Result();
        $result->setCheckItem($option->getParent());
        $result->setCheckOption($option);
        $result->setUsername($user->getUserIdentifier());

        return $result;
    }
}
