<?php

namespace viget\builder\models;

class SectionConfig
{
    public function __construct(
        public string  $name,
        public string  $type, // Technically an enum
        public array   $siteIds,
        public ?string $handle = null,
        public ?string $uriFormat = null,
        public bool    $hasUrls = true,
    )
    {
    }
}