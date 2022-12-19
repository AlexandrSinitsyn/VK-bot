<?php

namespace Bot\Attributes;

#[\Attribute] class ValidatorCommand
{
    public function __construct(public string $version = '1.0')
    {

    }
}
