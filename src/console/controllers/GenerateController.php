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
    private GeneratorConfig $generatorConfig;
    
    public function init(): void
    {
        parent::init();
        $this->generatorConfig = new GeneratorConfig();
    }
    
    public function options($actionID): array
    {
        return ['uriFormat', 'noUrl'];
    }
    
    public function actionSingle(string $name, string $handle = null): int
    {
        $service = new GeneratorService();
        $service->createSection(
            new SectionConfig(
                name: $name,
                type: Section::TYPE_SINGLE,
                siteIds: [
                    Craft::$app->getSites()->getPrimarySite()->id,
                ],
                handle: $handle,
                uriFormat: $this->uriFormat,
                hasUrls: $this->noTemplate === false,
            ),
            $this->generatorConfig,
        );
        return ExitCode::OK;
    }
    
    public function actionChannel(string $name, ?string $handle = null): int
    {
        $service = new GeneratorService();
        $service->createSection(
            new SectionConfig(
                name: $name,
                type: Section::TYPE_CHANNEL,
                siteIds: [
                    Craft::$app->getSites()->getPrimarySite()->id,
                ],
                handle: $handle,
                uriFormat: $this->uriFormat,
                hasUrls: $this->noTemplate === false,
            ),
            $this->generatorConfig,
        );
        return ExitCode::OK;
    }
}
