<?php

namespace Bot\Attributes;

#[\Attribute] class Controller
{
    public function __construct(public string $version = "1.0")
    {

    }
}
