<?php

namespace viget\generator\models;

class SectionConfig
{
    public function __construct(
        public string  $name,
        public string  $type, // Technically an enum
        public ?string $handle,
        public ?string $uriFormat,
        public string  $templatePath,
        public bool    $template = true,
    )
    {
    }
}