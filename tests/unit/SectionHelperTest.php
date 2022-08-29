<?php

namespace vigetbasetests\unit;

use Codeception\Test\Unit;
use craft\models\Section;
use craft\models\Section_SiteSettings;
use UnitTester;
use viget\builder\helpers\SectionHelper;
use viget\builder\models\SectionConfig;

class SectionHelperTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    public function createSectionDataProvider()
    {
        return [
            Section::TYPE_SINGLE => [Section::TYPE_SINGLE, 'test'],
            Section::TYPE_CHANNEL => [Section::TYPE_CHANNEL, 'test/{slug}'],
            Section::TYPE_STRUCTURE => [Section::TYPE_STRUCTURE, 'test/{slug}'],
        ];
    }
    
    /**
     * @dataProvider createSectionDataProvider
     */
    public function testCreateSection($sectionType, $uriFormat)
    {
        $this->assertEquals(
            SectionHelper::createSection(
                new SectionConfig(
                    name: 'Test',
                    type: $sectionType,
                    siteIds: [1],
                )
            ),
            new Section([
                'name' => 'Test',
                'handle' => 'test',
                'type' => $sectionType,
                'siteSettings' => [
                    new Section_SiteSettings([
                        'siteId' => 1,
                        'enabledByDefault' => true,
                        'hasUrls' => true,
                        'uriFormat' => $uriFormat,
                        'template' => '_elements/test.twig',
                    ])
                ],
            ])
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
    
    public function testCustomHandle()
    {
        $result = SectionHelper::createSection(
            new SectionConfig(
                name: 'Test',
                handle: 'customHandle',
                type: Section::TYPE_CHANNEL,
                siteIds: [1],
            )
        );
        
        $this->assertEquals(
            'customHandle',
            $result->handle,
        );
    }
    
    public function testCustomUriFormat()
    {
        $result = SectionHelper::createSection(
            new SectionConfig(
                name: 'Test',
                type: Section::TYPE_CHANNEL,
                siteIds: [1],
                uriFormat: '/custom-uri-format',
            )
        );
        
        $siteSettings = array_values($result->siteSettings)[0];
        
        $this->assertEquals(
            '/custom-uri-format',
            $siteSettings->uriFormat,
        );
    }
    
    /**
     * How to singularize templates for section types
     */
    public function singularizeDataProvider(): array
    {
        return [
            Section::TYPE_SINGLE => [Section::TYPE_SINGLE, '_elements/people.twig',],
            Section::TYPE_STRUCTURE => [Section::TYPE_STRUCTURE, '_elements/person.twig',],
            Section::TYPE_CHANNEL => [Section::TYPE_CHANNEL, '_elements/person.twig',],
        ];
    }
    
    /**
     * @dataProvider singularizeDataProvider
     */
    public function testAutoSingularizeTemplate($sectionType, $expectedResult)
    {
        $result = SectionHelper::createSection(
            new SectionConfig(
                name: 'People',
                type: $sectionType,
                siteIds: [1],
            )
        );
        
        $siteSettings = array_values($result->siteSettings)[0];
        
        $this->assertEquals(
            $expectedResult,
            $siteSettings->template
        );
    }
    
    public function testDisableAutoSingularizeTemplate()
    {
        $result = SectionHelper::createSection(
            new SectionConfig(
                name: 'People',
                type: Section::TYPE_CHANNEL,
                siteIds: [1],
                autoSingularizeTemplates: false,
            )
        );
        
        $siteSettings = array_values($result->siteSettings)[0];
        
        $this->assertEquals(
            '_elements/people.twig',
            $siteSettings->template
        );
    }
}