<?php

namespace Bot\Attributes;

#[\Attribute] class Service
{
    public function __construct(public string $version = '1.0')
    {

    }
}
