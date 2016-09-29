<?php

namespace Webmax\VelmaClient\Exception;

use Exception;
use BadMethodCallException;

class BadPacketDefinitionException extends BadMethodCallException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Bad packed definition detected', 1000);
    }
}
