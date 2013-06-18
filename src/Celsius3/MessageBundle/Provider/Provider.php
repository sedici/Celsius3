<?php

namespace Celsius3\MessageBundle\Provider;

use FOS\MessageBundle\Provider\Provider as BaseProvider;

class Provider extends BaseProvider
{

    public function getInboxThreadsQuery()
    {
        $participant = $this->getAuthenticatedParticipant();
        return $this->threadManager->getParticipantInboxThreadsQueryBuilder($participant);
    }

    public function getSentThreadsQuery()
    {
        $participant = $this->getAuthenticatedParticipant();
        return $this->threadManager->getParticipantSentThreadsQueryBuilder($participant);
    }

}

