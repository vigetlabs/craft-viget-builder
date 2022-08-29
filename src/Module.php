<?php

namespace viget\builder;

use Craft;
use craft\console\Application as CraftConsoleApplication;
use craft\events\RegisterTemplateRootsEvent;
use craft\web\View;
use viget\builder\console\controllers\GenerateController;
use viget\builder\controllers\ReportsController;
use viget\builder\services\SectionService;
use yii\base\BootstrapInterface;
use yii\base\Event;

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
        } else {
            Craft::$app->controllerMap['viget-builder-reports'] = [
                'class' => ReportsController::class,
            ];
        }
        
        
        Craft::info(
            'Viget Generator Loaded',
            __METHOD__
        );
        
        $this->setComponents([
            'sectionService' => SectionService::class,
        ]);
        
        // Register Module Templates
        Event::on(
            View::class,
            View::EVENT_REGISTER_SITE_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $e) {
                if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
                    $e->roots[$this->id] = $baseDir;
                }
            }
        );
    }
    
    public function getSectionService(): SectionService
    {
        return $this->get('sectionService');
    }
}
