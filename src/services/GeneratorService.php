<?php

namespace viget\builder\services;

use Craft;
use craft\models\Site;
use Exception;
use Throwable;
use viget\builder\helpers\GenerateHelper;
use viget\builder\helpers\SectionHelper;
use viget\builder\models\GeneratorConfig;
use viget\builder\models\SectionConfig;
use yii\base\BaseObject;

class GeneratorService extends BaseObject
{

    public bool $saveSections = false;

    /**
     * @param Site $primarySite
     * @throws Throwable
     */
    public function createSection(
        SectionConfig   $config,
        GeneratorConfig $generatorConfig,
    ): void
    {
        $db = Craft::$app->db;
        $transaction = $db->beginTransaction();

        if (!$transaction) {
            throw new \yii\db\Exception('Could not acquire DB transaction');
        }

        try {
            $section = SectionHelper::createSection($config);
            $sectionSiteSettings = array_values($section->siteSettings)[0];

            if ($this->saveSections) {
                $success = Craft::$app->sections->saveSection($section);

                if (!$success) {
                    // TODO how would you handle this?
                    print_r($section->getErrors());
                    throw new Exception("Couldn't save section");
                }
                $defaultEntryType = $section->getEntryTypes()[0];
                $defaultEntryType->hasTitleField = true;

                $success = Craft::$app->sections->saveEntryType($defaultEntryType);

                if (!$success) {
                    print_r($defaultEntryType->getErrors());
                    throw new Exception("Couldn't save entry type");
                }
            }

            if ($config->hasUrls) {
                $templateContent = GenerateHelper::renderTemplate(
                    Craft::getAlias('@viget/generator/templates/_scaffold/template.twig'),
                    [
                        'layout' => $generatorConfig->layout,
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
}