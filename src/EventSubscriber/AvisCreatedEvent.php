<?php

namespace App\EventSubscriber;

use App\Entity\Avis;
use Symfony\Component\EventDispatcher\Event;

/**
 * The avis.created event is dispatched each time an avis is created
 * in the system.
 */
class AvisCreatedEvent extends Event
{
    const NAME = 'avis.created';

    protected $avis;

    public function __construct(Avis $avis)
    {
        $this->avis = $avis;
    }

    public function getAvis()
    {
        return $this->avis;
    }
}
