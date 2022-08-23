<?php

namespace viget\generator\models;

class GeneratorConfig
{
    public function __construct(
        public string $layout = '_layouts/app',
    )
    {
        
    }
}