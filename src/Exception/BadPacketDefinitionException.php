<?php

namespace Webmax\VelmaClient\Exception;

use Exception;
use BadMethodCallException;

/**
 * Bad packet definition exception
 *
 * @author Frank Bardon Jr. <frankbardon@gmail.com>
 * @todo Fully unit test.
 */
class BadPacketDefinitionException extends BadMethodCallException
{
    /**
     * Exception constructor
     *
     * @param Exception|null $previous
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('Bad packed definition detected', 1000);
    }
}
