<?php
namespace vigetbasetests\unit\console;

use Craft;
use craft\helpers\FileHelper;
use craft\test\console\ConsoleTest;
use viget\base\Module;
use yii\base\InvalidConfigException;
use yii\console\ExitCode;

class GenerateControllerTest extends ConsoleTest
{
    private $siteTemplatesPath;
    private $templatesRoot;
    private $partialsRoot;
    private $partsKitRoot;

    // public function _fixtures(): array
    // {
    //     return [
    //         'entryTypes' => [
    //             'class' => EntryTypesFixture::class,
    //         ],
    //         'sections' => [
    //             'class' => SectionsFixture::class,
    //         ],
    //     ];
    // }

    protected function _before()
    {
//        $templatePath = Craft::$app->getPath()->getSiteTemplatesPath();
        // $templatePrefix = Module::$config['scaffold']['templatePrefix'];
        // $partsKitDir = Module::$config['partsKit']['directory'];
//        $this->siteTemplatesPath = $templatePath;
        // $this->partialsRoot = $this->siteTemplatesPath . '/_partials'; // TODO - config?
        // $this->templatesRoot = $this->siteTemplatesPath . '/_elements'; // TODO - config?
        // $this->partsKitRoot = $this->siteTemplatesPath . '/' . $partsKitDir;
    }

//    protected function _after()
//    {
//        FileHelper::removeDirectory($this->siteTemplatesPath);
//    }

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

        $section = Craft::$app->getSections()->getSectionByHandle('test');
        
        $this->assertNotNull($section);
        
        // $this->assertFileExists($this->partialsRoot . '/foo.html');
        // $this->assertFileExists($this->partsKitRoot . '/foo/default.html');

        // $partsKitContent = file_get_contents($this->partsKitRoot . '/foo/default.html');

        // $this->assertStringContainsString(
        //     '_partials/foo',
        //     $partsKitContent
        // );
    }
}
