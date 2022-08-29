<?php

namespace viget\builder\controllers;

use Craft;
use craft\base\Field;
use craft\base\FieldInterface;
use craft\elements\Entry;
use craft\helpers\StringHelper;
use craft\models\EntryType;
use craft\models\FieldGroup;
use craft\models\Section;
use craft\web\Controller;
use Illuminate\Support\Collection;
use yii\web\Response;

class ReportsController extends Controller
{
    public function actionFields(): Response
    {
        $fieldsService = Craft::$app->getFields();
        $fieldGroups = $fieldsService->getAllGroups();
        
        $fieldsByGroup = (new Collection($fieldGroups))
            ->map(static function (FieldGroup $group): array {
                return [
                    'name' => $group->name,
                    'fields' => (new Collection($group->getFields()))
                        ->map(fn(Field $field) => [
                            'name' => $field->name,
                            'handle' => $field->handle,
                            'type' => self::_cleanFieldType($field::class),
                            'settings' => $field->settings,
                        ]),
                ];
            })
            ->all();
        
        return $this->renderTemplate('_reports/fields', [
            'fieldsByGroup' => $fieldsByGroup
        ]);

//        return $this->asJson($fieldsByGroup);
    }
    
    public function actionSections(): Response
    {
        $sectionsService = Craft::$app->getSections();
        
        $sections = (new Collection($sectionsService->getAllSections()))
            ->map(static function (Section $section) {
                // TODO make multisite
                $siteSettings = array_values($section->getSiteSettings())[0];
                return [
                    'name' => $section->name,
                    'handle' => $section->handle,
                    'type' => $section->type,
                    'siteSettings' => $siteSettings,
                    'urlFormat' => $siteSettings,
                    'entryTypes' => (new Collection($section->getEntryTypes()))
                        ->map(function (EntryType $entryType) use ($section) {
                            $fieldLayout = $entryType->getFieldLayout();
                            return [
                                'name' => $entryType->name,
                                'handle' => $entryType->handle,
                                'fields' => (new Collection($fieldLayout->getCustomFields()))
                                    ->map(function (FieldInterface $field) {
                                        return [
                                            'name' => $field->name,
                                            'handle' => $field->handle,
                                            'type' => self::_cleanFieldType($field::class),
                                            'settings' => $field->settings,
                                        ];
                                    }),
                            ];
                        })
                ];
            })
            ->all();
        
        return $this->renderTemplate('_reports/sections', [
            'sections' => $sections,
        ]);
    }
    
    public function actionPreviewSection(): Response
    {
        $sectionHandle = $this->request->getRequiredQueryParam('section');
        $entryTypeHandle = $this->request->getRequiredQueryParam('entryType');
        
        $entry = Entry::find()
            ->section($sectionHandle)
            ->type($entryTypeHandle)
            ->one();
        
        return $this->redirect($entry->url);
    }
    
    private static function _cleanFieldType(string $fieldClass): string
    {
        return StringHelper::replace($fieldClass, 'craft\\fields\\', '');
    }
}