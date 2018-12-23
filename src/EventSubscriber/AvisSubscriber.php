<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AvisSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            AvisCreatedEvent::NAME => 'onCreateAvis',
        ];
    }

    public function onCreateAvis(AvisCreatedEvent $event)
    {
        $avis = $event->getAvis();
        $message = (new \Swift_Message('Message du Site'))
                ->setFrom(getenv('SEND_TO'))
                ->setTo($avis->getEmail())
                ->setReplyTo(getenv('SEND_TO'))
                ->setBody('Un commentaire a été mis par '.$avis->getPseudo().' sur votre recette '.$avis->getRecette()->getTitre())
                ;
        $this->mailer->send($message);
    }
}
