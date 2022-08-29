<?php

namespace vigetbasetests\unit\console;

use Craft;
use craft\helpers\FileHelper;
use craft\test\console\ConsoleTest;
use viget\builder\Module;
use viget\builder\services\SectionService;
use yii\base\InvalidConfigException;
use yii\console\ExitCode;

class GenerateControllerTest extends ConsoleTest
{
    
    private SectionService $sectionService;
    private string $templatesDir;
    
    protected function _before()
    {
        $this->sectionService = Module::getInstance()->getSectionService();
        $this->sectionService->saveSections = false;
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
            'test-single',
        ])
            ->exitCode(ExitCode::OK)
            ->run();
        
        $this->assertFileExists($this->templatesDir . '/_elements/test-single.twig');
    }
    
    public function testCreateChannel()
    {
        $this->consoleCommand('vigenerate/channel', [
            'test-channel',
        ])
            ->exitCode(ExitCode::OK)
            ->run();
        
        $this->assertFileExists($this->templatesDir . '/_elements/test-channel.twig');
    }
    
    public function testCreateStructure()
    {
        $this->consoleCommand('vigenerate/structure', [
            'test-structure',
        ])
            ->exitCode(ExitCode::OK)
            ->run();
        
        $this->assertFileExists($this->templatesDir . '/_elements/test-structure.twig');
    }
}
