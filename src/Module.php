<?php

namespace viget\builder;

use Craft;
use craft\console\Application as CraftConsoleApplication;
use viget\builder\console\controllers\GenerateController;
use viget\builder\services\SectionService;
use yii\base\BootstrapInterface;

/**
 * @property SectionService $sectionService
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();
        $this->bootstrap(Craft::$app);
    }
    
    public function bootstrap($app)
    {
        Craft::setAlias('@viget/generator', __DIR__);
        self::setInstance($this);
        
        // Auto-bootstrapping requires that we
        // manually register our controller paths
        if (Craft::$app instanceof CraftConsoleApplication) {
            // What should we name this?
            Craft::$app->controllerMap['vigenerate'] = [
                'class' => GenerateController::class,
            ];
        }
        
        Craft::info(
            'Viget Generator Loaded',
            __METHOD__
        );
        
        $this->setComponents([
            'sectionService' => SectionService::class,
        ]);
    }
    
    public function getSectionService(): SectionService
    {
        return $this->get('sectionService');
    }
}
