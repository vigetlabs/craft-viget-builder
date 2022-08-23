<?php

namespace viget\generator\services;

use Craft;
use craft\helpers\StringHelper;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use craft\models\Site;
use Exception;
use Throwable;
use viget\generator\helpers\GenerateHelper;
use viget\generator\models\GeneratorConfig;
use viget\generator\models\SectionConfig;

class GeneratorService
{
    /**
     * @param Site $primarySite
     * @throws Throwable
     */
    public function createSection(
        SectionConfig   $config,
        GeneratorConfig $generatorConfig,
    ): void
    {
        $primarySite = Craft::$app->getSites()->getPrimarySite();
        
        $handle = $config->handle ?? StringHelper::camelCase($config->name);
        $slug = StringHelper::slugify($config->name);
        $uriFormat = $slug;
        
        if ($config->type === Section::TYPE_CHANNEL || $config->type === SECTION::TYPE_STRUCTURE) {
            $uriFormat = $slug . '/{slug}';
        }
        
        // Override auto URI format if passed as option
        if ($config->uriFormat !== null || $config->template === false) {
            $uriFormat = $config->uriFormat;
        }
        
        $hasUriFormat = (bool)$uriFormat;
        $hasUrls = $hasUriFormat;
        $templatePath = $config->template && $hasUrls ? "_elements/{$slug}.twig" : null;
        
        $section = new Section([
            'name' => $config->name,
            'handle' => $handle,
            'type' => $config->type,
            'siteSettings' => [
                new Section_SiteSettings([
                    'siteId' => $primarySite->id,
                    'enabledByDefault' => true,
                    'hasUrls' => $hasUrls,
                    'uriFormat' => $hasUriFormat ? $uriFormat : null,
                    'template' => $templatePath,
                ]),
            ]
        ]);
        
        $success = false;
        
        try {
            $success = Craft::$app->sections->saveSection($section);
        } catch (Exception $e) {
            throw $e;
        }
        
        if (!$success) {
            // TODO how would you handle this?
            print_r($section->getErrors());
            throw new Exception("Couldn't save section");
        }
        
        $defaultEntryType = $section->getEntryTypes()[0];
        
        $defaultEntryType->hasTitleField = true;
        
        $success = Craft::$app->sections->saveEntryType($defaultEntryType);
        
        if (!$success) {
            print_r($defaultEntryType->getErrors());
            throw new Exception("Couldn't save entry type");
        }
        
        if ($templatePath) {
            $templateContent = GenerateHelper::renderTemplate(
                $config->templatePath,
                [
                    'layout' => $generatorConfig->layout,
                ]
            );
            
            GenerateHelper::writeToUsersTemplateDirectory($templatePath, $templateContent);
        }
    }
}