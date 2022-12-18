<?php

namespace Bot\Attributes;

#[\Attribute] class Repository
{
    public function __construct(public string $version = '1.0')
    {

    }
}
