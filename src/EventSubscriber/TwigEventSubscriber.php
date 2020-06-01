<?php

namespace App\EventSubscriber;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;


class TwigEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var ConferenceRepository
     */
    private $conferenceRepository;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
        $this->twig = $twig;
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $this->twig->addGlobal('conferences', $this->conferenceRepository->findAll());
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
