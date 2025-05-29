<?php

namespace AppleWallet\Passbook\Type;

use AppleWallet\Passbook\Pass\Pass;

class EventTicket extends Pass
{
    public function getType(): string
    {
        return 'eventTicket';
    }
} 