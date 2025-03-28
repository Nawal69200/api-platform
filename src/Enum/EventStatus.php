<?php

namespace App\Enum;

enum EventStatus: string
{
    case SCHEDULED = 'programme';
    case IN_PROGRESS = 'en_cours';
    case COMPLETED = 'termine';
    case CANCELLED = 'annule';
}
