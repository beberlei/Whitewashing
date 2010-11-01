<?php

namespace Whitewashing\DateTime;

class ImmutableException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Cannot modify DateTime instance, its immutable!");
    }
}