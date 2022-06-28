<?php

namespace App\EventSubscriber;

use App\Repository\ProfileRepository;
use App\Repository\TopicRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;

    private $topicRepository;
    private $profileRepository;

    private $security;

    public function __construct(Environment $twig, TopicRepository $topicRepository, ProfileRepository $profileRepository, Security $security)
    {
        $this->twig = $twig;
        $this->topicRepository = $topicRepository;
        $this->profileRepository = $profileRepository;
        $this->security = $security;
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $user = $this->security->getUser();
        if(!$user)  {
            return;
        }

        $profile = $this->profileRepository->findOneBy(['email' => $user->getUserIdentifier()]);
        if($profile) {
            $this->twig->addGlobal('topics', $this->topicRepository->findBy(['profile' => $profile]));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
