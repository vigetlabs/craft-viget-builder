<?php

namespace viget\generator\models;

class GeneratorConfig
{
    // Eventually this should be based on a module config file
    public function __construct(
        public string $layout = '_layouts/app',
    )
    {

    }
}