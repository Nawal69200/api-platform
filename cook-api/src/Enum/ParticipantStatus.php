<?php

namespace App\Enum;

enum ParticipantStatus: string
{
    case PENDING = 'en_attente';
    case CONFIRMED = 'inscrit';
    case CANCELLED = 'annule';
}
