<?php

namespace viget\builder;

use Craft;
use craft\console\Application as CraftConsoleApplication;
use viget\builder\console\controllers\GenerateController;
use viget\builder\services\GeneratorService;
use yii\base\BootstrapInterface;

/**
 * @property GeneratorService $generator
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
            Craft::$app->controllerMap['vigenerate'] = [
                'class' => GenerateController::class,
            ];
        }

        Craft::info(
            'Viget Generator Loaded',
            __METHOD__
        );

        $this->setComponents([
            'generator' => GeneratorService::class,
        ]);
    }

    public function getGenerator(): GeneratorService
    {
        return $this->get('generator');
    }
}
