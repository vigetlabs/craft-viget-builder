<?php

namespace viget\builder\models;

class GeneratorConfig
{
    // Eventually this should be based on a module config file
    public function __construct(
        public string $defaultLayout = '_layouts/app',
        public string $componentPath = '_components',
        public string $partsKitPath = 'parts-kit',
        public string $partsKitLayout = 'viget-base/_layouts/parts-kit',
    )
    {

    }
}