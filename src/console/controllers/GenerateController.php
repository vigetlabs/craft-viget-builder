<?php

namespace viget\builder\console\controllers;

use Craft;
use craft\console\Controller;
use craft\errors\EntryTypeNotFoundException;
use craft\errors\SectionNotFoundException;
use craft\errors\SiteNotFoundException;
use craft\models\Section;
use RuntimeException;
use Throwable;
use viget\builder\models\SectionConfig;
use viget\builder\Module;
use viget\builder\services\ComponentService;
use viget\builder\services\SectionService;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\ExitCode;

class GenerateController extends Controller
{
    
    public ?string $uriFormat = null;
    public bool $noTemplate = false;
    public SectionService $sectionService;
    public ComponentService $componentService;
    
    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        parent::init();
        $module = Module::getInstance();
        
        if (!$module) {
            throw new RuntimeException('Cannot find module instance');
        }
        
        $this->sectionService = $module->getSectionService();
        $this->componentService = $module->getComponentService();
        
    }
    
    public function options($actionID): array
    {
        return ['uriFormat', 'noUrl'];
    }
    
    /**
     * @throws SiteNotFoundException
     * @throws SectionNotFoundException
     * @throws Throwable
     * @throws \yii\db\Exception
     * @throws EntryTypeNotFoundException
     * @throws ErrorException
     * @throws Exception
     */
    public function actionSingle(string $name, string $handle = null): int
    {
        $this->sectionService->createSection(
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
        );
        return ExitCode::OK;
    }
    
    /**
     * @throws SiteNotFoundException
     * @throws SectionNotFoundException
     * @throws Throwable
     * @throws \yii\db\Exception
     * @throws EntryTypeNotFoundException
     * @throws Exception
     * @throws ErrorException
     */
    public function actionChannel(string $name, ?string $handle = null): int
    {
        $this->sectionService->createSection(
            new SectionConfig(
                name: $name,
                type: Section::TYPE_CHANNEL,
                siteIds: [
                    Craft::$app->getSites()->getPrimarySite()->id,
                ],
                handle: $handle,
                uriFormat: $this->uriFormat,
                hasUrls: $this->noTemplate === false,
            )
        );
        return ExitCode::OK;
    }
    
    /**
     * @throws SiteNotFoundException
     * @throws SectionNotFoundException
     * @throws \yii\db\Exception
     * @throws Throwable
     * @throws EntryTypeNotFoundException
     * @throws Exception
     * @throws ErrorException
     */
    public function actionStructure(string $name, ?string $handle = null): int
    {
        $this->sectionService->createSection(
            new SectionConfig(
                name: $name,
                type: Section::TYPE_STRUCTURE,
                siteIds: [
                    Craft::$app->getSites()->getPrimarySite()->id,
                ],
                handle: $handle,
                uriFormat: $this->uriFormat,
                hasUrls: $this->noTemplate === false,
            )
        );
        return ExitCode::OK;
    }
    
    public function actionComponent(string $name): int
    {
        $this->componentService->createComponent($name);
        
        return ExitCode::OK;
    }
}
