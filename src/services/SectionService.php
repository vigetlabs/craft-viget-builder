<?php

namespace viget\builder\services;

use Craft;
use craft\errors\EntryTypeNotFoundException;
use craft\errors\SectionNotFoundException;
use Exception;
use RuntimeException;
use Throwable;
use viget\builder\helpers\GenerateHelper;
use viget\builder\helpers\SectionHelper;
use viget\builder\models\GeneratorConfig;
use viget\builder\models\SectionConfig;
use yii\base\BaseObject;
use yii\base\ErrorException;
use yii\db\Exception as DbException;

class SectionService extends BaseObject
{
    
    /** @var bool Saving sections fails during unit testing, so we have the option to turn that off */
    public bool $saveSections = true;
    
    public GeneratorConfig $generatorConfig;
    
    public function init()
    {
        parent::init();
        
        $this->generatorConfig = new GeneratorConfig();
    }
    
    /**
     * @param SectionConfig $config
     * @throws EntryTypeNotFoundException
     * @throws ErrorException
     * @throws SectionNotFoundException
     * @throws Throwable
     * @throws \yii\base\Exception
     * @throws DbException
     */
    public function createSection(
        SectionConfig $config,
    ): void
    {
        $db = Craft::$app->db;
        $transaction = $db->beginTransaction();
        
        if (!$transaction) {
            throw new DbException('Could not acquire DB transaction');
        }
        
        try {
            $section = SectionHelper::createSection($config);
            $sectionSiteSettings = array_values($section->siteSettings)[0];
            
            if ($this->saveSections) {
                $success = Craft::$app->sections->saveSection($section);
                
                if (!$success) {
                    // TODO how would you handle this?
                    print_r($section->getErrors());
                    throw new RuntimeException("Couldn't save section");
                }
                $defaultEntryType = $section->getEntryTypes()[0];
                $defaultEntryType->hasTitleField = true;
                
                $success = Craft::$app->sections->saveEntryType($defaultEntryType);
                
                if (!$success) {
                    print_r($defaultEntryType->getErrors());
                    throw new RuntimeException("Couldn't save entry type");
                }
            }
            
            if ($config->hasUrls) {
                $templateContent = GenerateHelper::renderTemplate(
                    Craft::getAlias('@viget/generator/templates/_scaffold/template.twig'),
                    [
                        'layout' => $this->generatorConfig->layout,
                    ]
                );
                
                GenerateHelper::writeToUsersTemplateDirectory(
                    $sectionSiteSettings->template,
                    $templateContent
                );
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
    
    public function createPartial(array $options): void
    {
    
    }
}