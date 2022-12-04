<?php

namespace App\Service;

use App\Entity\Invite;
use App\Entity\Profile;
use App\Model\Message;
use App\Model\Subscribe;
use App\Repository\ProfileRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Subscribe Service is a default module.
 * Should be implemented as interface (ModuleInterface) in a next release
 */
class SubscribeService
{
    private $doctrine;

    private $profiles;

    private $messageService;

    public function __construct(ManagerRegistry $doctrine, ProfileRepository $profiles, MessageService $messageService)
    {
        $this->doctrine = $doctrine;
        $this->profiles = $profiles;
        $this->messageService = $messageService;
    }

    public function validateSubscription(Subscribe $subscribe) : bool
    {
        //TODO: add validation criteria
        if(!$subscribe->getType()) {
            return false;
        }

        return true;
    }

    public function initSubscribe(Subscribe $subscribe) : Invite
    {
        if(!$this->validateSubscription($subscribe)) {
            //TODO: throw validation results
            throw new \RuntimeException("Subscribe invalid");
        }

        //TODO: check find criteria
        $profile = $this->profiles->findOneBy([$subscribe->getType() => $subscribe->email]);

        if(!$profile) {
            $profile = (new Profile())
                ->setName($subscribe->name)
                ->setEmail($subscribe->email)
                ->setTimezone(date_default_timezone_get())
                ->setLocale($subscribe->locale)
                ->setActive(false)
            ;
        }

        $this->doctrine->getManager()->persist($profile);

        //subscribe request
        $invite = (new Invite())
            ->setEmail($subscribe->email)
            ->setName($subscribe->name)
            ->setDescription($subscribe->getType())
            ->setLifetime(1000)
            ->setProfile($profile)
        ;

        $email = $this->messageService->compose(new Message($subscribe->email, $subscribe->locale));

        $this->messageService->send($email);

        $this->doctrine->getManager()->persist($invite);
        $this->doctrine->getManager()->flush();

        return $invite;
    }
}
