<?php

namespace viget\generator\console\controllers;

use Craft;
use craft\console\Controller;
use craft\helpers\FileHelper;
use craft\models\Section;
use Exception;
use viget\generator\models\GeneratorConfig;
use viget\generator\models\SectionConfig;
use viget\generator\services\GeneratorService;
use yii\console\ExitCode;

class GenerateController extends Controller
{

    public ?string $uriFormat = null;
    public bool $noTemplate = false;

    private $templatesDir;
    private $scaffoldTemplatesDir;
    private $partsKitDir;
    private $defaultFileExtension;

    private GeneratorConfig $generatorConfig;

    public function init(): void
    {
        parent::init();
        $this->generatorConfig = new GeneratorConfig();
        $this->scaffoldTemplatesDir = FileHelper::normalizePath(__dir__ . '/../../templates/_scaffold');
    }

//    public function init(): void
//    {
//        $templatePrefix = self::getConfig('templatePrefix', 'scaffold');
//        $this->templatesDir = Craft::$app->path->getSiteTemplatesPath() . $templatePrefix;
//        $this->partsKitDir = self::getConfig('directory');
//        $this->defaultFileExtension = '.twig';
//        parent::init();
//    }

    public function options($actionID): array
    {
        return ['uriFormat', 'noTemplate'];
    }

//    /**
//     * Get a config item either the default or from the config file
//     *
//     * @param string $key
//     * @return string|array|null
//     */
//    public static function getConfig(string $key, string $section = 'partsKit')
//    {
//        return Module::$config[$section][$key] ?? null;
//    }
//
    public function actionSingle(string $name, string $handle = null): int
    {
        $db = Craft::$app->db;
        // $transaction = $db->beginTransaction();
        $service = new GeneratorService();
        
        try {
            $service->createSection(
                new SectionConfig(
                    name: $name,
                    type: Section::TYPE_SINGLE,
                    handle: $handle,
                    uriFormat: $this->uriFormat,
                    templatePath: $this->scaffoldTemplatesDir . DIRECTORY_SEPARATOR . 'template.twig',
                    template: $this->noTemplate === false,
                ),
                $this->generatorConfig,
            );

        } catch (Exception $e) {
            throw $e;
            // $transaction?->rollBack();
        }

        // $transaction?->commit();

        return ExitCode::OK;
    }
    
    public function actionChannel(string $name, ?string $handle = null): int
    {
        $db = Craft::$app->db;
        // $transaction = $db->beginTransaction();
        $service = new GeneratorService();
    
        try {
            $service->createSection(
                new SectionConfig(
                    name: $name,
                    type: Section::TYPE_CHANNEL,
                    handle: $handle,
                    uriFormat: $this->uriFormat,
                    templatePath: $this->scaffoldTemplatesDir . DIRECTORY_SEPARATOR . 'template.twig',
                    template: $this->noTemplate === false,
                ),
                $this->generatorConfig,
            );
        
        } catch (Exception $e) {
            throw $e;
            // $transaction?->rollBack();
        }
    
        // $transaction?->commit();
    
        return ExitCode::OK;
    }

//    public function actionChannel(string $name, string $handle = null): int
//    {
//        $db = Craft::$app->db;
//        $transaction = $db->beginTransaction();
//
//        try {
//            $this->_creatSection($name, Section::TYPE_CHANNEL, $handle);
//        } catch (Exception $e) {
//            $transaction->rollBack();
//        }
//
//        $transaction->commit();
//
//        return ExitCode::OK;
//    }



//    private function _loadScaffoldTemplate(string $path)
//    {
//        return file_get_contents($this->scaffoldTemplatesDir . '/' . $path);
//    }
//
//    private function _writeFile(string $path, string $content): void
//    {
//        $fullPath = $this->templatesDir . '/' . $path;
//
//        if (file_exists($fullPath)) {
//            $this->stdout("File already exists for $fullPath" . PHP_EOL, Console::FG_RED);
//            return;
//        }
//
//        FileHelper::writeToFile($fullPath, $content);
//    }
}
