<?php

namespace vigetbasetests\unit;

use Codeception\Test\Unit;
use Craft;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use UnitTester;
use viget\generator\helpers\SectionHelper;
use viget\generator\models\SectionConfig;
use yii\helpers\ArrayHelper;

class SectionHelperTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    // tests
    public function testGenerateSingle()
    {
        $this->assertEquals(
            SectionHelper::createSection(
                new SectionConfig(
                    name: 'Test',
                    type: Section::TYPE_SINGLE,
                    siteIds: [1],
                )
            ),
            new Section([
                'name' => 'Test',
                'handle' => 'test',
                'type' => Section::TYPE_SINGLE,
                'siteSettings' => [
                    new Section_SiteSettings([
                        'siteId' => 1,
                        'enabledByDefault' => true,
                        'hasUrls' => true,
                        'uriFormat' => 'test',
                        'template' => '_elements/test.twig',
                    ])
                ],
            ])
        );
    }
    
    public function testGenerateChannel()
    {
        $this->assertEquals(
            SectionHelper::createSection(
                new SectionConfig(
                    name: 'Test',
                    type: Section::TYPE_CHANNEL,
                    siteIds: [1],
                )
            ),
            new Section([
                'name' => 'Test',
                'handle' => 'test',
                'type' => Section::TYPE_CHANNEL,
                'siteSettings' => [
                    new Section_SiteSettings([
                        'siteId' => 1,
                        'enabledByDefault' => true,
                        'hasUrls' => true,
                        'uriFormat' => 'test/{slug}',
                        'template' => '_elements/test.twig',
                    ])
                ],
            ])
        );
    }
    
    public function testGenerateStructure()
    {
        $this->assertEquals(
            new Section([
                'name' => 'Test',
                'handle' => 'test',
                'type' => Section::TYPE_STRUCTURE,
                'siteSettings' => [
                    new Section_SiteSettings([
                        'siteId' => 1,
                        'enabledByDefault' => true,
                        'hasUrls' => true,
                        'uriFormat' => 'test/{slug}',
                        'template' => '_elements/test.twig',
                    ])
                ],
            ]),
            SectionHelper::createSection(
                new SectionConfig(
                    name: 'Test',
                    type: Section::TYPE_STRUCTURE,
                    siteIds: [1],
                )
            ),
        );
    }
    
    public function testHasUriFalse()
    {
        $result = SectionHelper::createSection(
            new SectionConfig(
                name: 'Test',
                type: Section::TYPE_CHANNEL,
                siteIds: [1],
                hasUrls: false,
            )
        );
        
        $siteSettings = array_values($result->siteSettings)[0];
        
        $this->assertEquals(
            [
                'uriFormat' => null,
                'template' => null,
                'hasUrls' => false,
            ],
            [
                'hasUrls' => $siteSettings->hasUrls,
                'uriFormat' => $siteSettings->uriFormat,
                'template' => $siteSettings->template,
            ],
        );
    }
}