<?php

namespace viget\generator\helpers;

use Craft;
use craft\helpers\StringHelper;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use Illuminate\Support\Collection;
use viget\generator\models\SectionConfig;

class SectionHelper
{
    public static function createSection(SectionConfig $config): Section
    {
//        $primarySite = Craft::$app->getSites()->getPrimarySite();
        
        $handle = $config->handle ?? StringHelper::camelCase($config->name);
        $slug = StringHelper::slugify($config->name);
        
        $defaultUriFormat = match ($config->type) {
            Section::TYPE_SINGLE => $slug,
            default => $slug . '/{slug}',
        };
        
        $uriFormat = $config->uriFormat ?? $defaultUriFormat;
        $templatePath = $config->hasUrls ? "_elements/{$slug}.twig" : null;
        
        return new Section([
            'name' => $config->name,
            'handle' => $handle,
            'type' => $config->type,
            'siteSettings' =>
                (new Collection($config->siteIds))->map(
                    fn(int $siteId) => new Section_SiteSettings([
                        'siteId' => $siteId,
                        'enabledByDefault' => true,
                        'hasUrls' => $config->hasUrls,
                        'uriFormat' => $config->hasUrls ? $uriFormat : null,
                        'template' => $templatePath,
                    ])
                )->all()
        ]);
    }
}