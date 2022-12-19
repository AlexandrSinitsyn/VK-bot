<?php

namespace Bot\Attributes;

#[\Attribute] class Validator
{
    public function __construct(public string $version = '1.0')
    {

    }
}
