<?php

namespace vigetbasetests\unit\console;

use Craft;
use craft\helpers\FileHelper;
use craft\test\console\ConsoleTest;
use viget\builder\Module;
use viget\builder\services\GeneratorService;
use yii\base\InvalidConfigException;
use yii\console\ExitCode;

class GenerateControllerTest extends ConsoleTest
{

    private GeneratorService $generatorService;
    private string $templatesDir;

    protected function _before()
    {
        $this->generatorService = Module::getInstance()->getGenerator();
        $this->generatorService->saveSections = false;
        $this->templatesDir = Craft::$app->path->getSiteTemplatesPath();
    }

    protected function _after()
    {
        FileHelper::clearDirectory($this->templatesDir, [
            'except' => ['.gitkeep', '.gitignore'],
        ]);
    }

    /**
     * @throws InvalidConfigException
     */
    public function testCreateSingle()
    {
        $this->consoleCommand('vigenerate/single', [
            'test',
        ])
            ->exitCode(ExitCode::OK)
            ->run();

        $this->assertFileExists($this->templatesDir . '/_elements/test.twig');
    }
}
