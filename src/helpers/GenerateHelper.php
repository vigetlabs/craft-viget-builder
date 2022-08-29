<?php

namespace viget\builder\helpers;

use Craft;
use craft\helpers\FileHelper;
use Exception;
use RuntimeException;
use yii\base\ErrorException;

class GenerateHelper
{
    
    public static function renderTemplate(string $templatePath, array $vars): string
    {
        $template = file_get_contents($templatePath);
        
        $patterns = array_map(static function ($item) {
            return "/%%$item%%/";
        }, array_values(array_flip($vars)));
        
        $replacements = array_values($vars);
        
        return preg_replace($patterns, $replacements, $template);
    }
    
    /**
     * @throws \yii\base\Exception
     * @throws ErrorException
     */
    public static function writeToUsersTemplateDirectory(string $path, string $content): void
    {
        $fullPath = Craft::$app->path->getSiteTemplatesPath() . '/' . $path;
        
        if (file_exists($fullPath)) {
            throw new RuntimeException("File already exists for $fullPath");
        }
        
        FileHelper::writeToFile($fullPath, $content);
    }
}