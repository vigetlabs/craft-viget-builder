<?php

namespace viget\generator\helpers;

use Craft;
use craft\helpers\FileHelper;

class GenerateHelper
{
    
    public static function renderTemplate(string $templatePath, array $vars): string
    {
        $template = file_get_contents($templatePath);
        
        $patterns = array_map(function ($item) {
            return "/%%$item%%/";
        }, array_values(array_flip($vars)));
        
        $replacements = array_values($vars);
        
        return preg_replace($patterns, $replacements, $template);
    }
    
    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\ErrorException
     */
    public static function writeToUsersTemplateDirectory(string $path, string $content): void
    {
        $fullPath = Craft::$app->path->getSiteTemplatesPath() . '/' . $path;
        
        if (file_exists($fullPath)) {
            throw new \Exception("File already exists for $fullPath");
        }
        
        FileHelper::writeToFile($fullPath, $content);
    }
    
    public static function parseInput(string $input): array
    {
        $cleanInput = trim($input, DIRECTORY_SEPARATOR);
        $split = explode(DIRECTORY_SEPARATOR, $cleanInput);
        $path = implode(DIRECTORY_SEPARATOR, array_slice($split, 0, -1));
        $filename = self::removeFileExtension(end($split));
        
        return [
            'path' => FileHelper::normalizePath($path),
            'filename' => FileHelper::sanitizeFilename($filename),
        ];
    }
    
    public static function removeFileExtension(string $filename): string
    {
        $explode = explode('.', $filename);
        if (count($explode) === 1) {
            return $filename;
        }
        
        return implode('.', array_slice($explode, 0, -1));
    }
}