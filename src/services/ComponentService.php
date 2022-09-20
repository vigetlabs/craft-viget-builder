<?php

namespace viget\builder\services;

use Craft;
use viget\builder\helpers\GenerateHelper;
use viget\builder\models\GeneratorConfig;

class ComponentService extends \yii\base\BaseObject
{
    private GeneratorConfig $generatorConfig;
    
    public function init()
    {
        parent::init();
        
        // TODO make this central to the module
        $this->generatorConfig = new GeneratorConfig();
    }
    
    public function createComponent(string $name)
    {
        $componentContent = GenerateHelper::renderTemplate(
            Craft::getAlias('@viget/generator/templates/_scaffold/component.twig'),
            [
                'name' => $name,
            ]
        );
        
        GenerateHelper::writeToUsersTemplateDirectory(
            "{$this->generatorConfig->componentPath}/{$name}.twig",
            $componentContent
        );
        
        $partsKitPageContent = GenerateHelper::renderTemplate(
            Craft::getAlias('@viget/generator/templates/_scaffold/parts-kit-page.twig'),
            [
                'componentName' => $name,
                'componentImport' => "{$this->generatorConfig->componentPath}/{$name}.twig",
                'partsKitLayout' => $this->generatorConfig->partsKitLayout,
            ]
        );
        
        GenerateHelper::writeToUsersTemplateDirectory(
            "{$this->generatorConfig->partsKitPath}/{$name}/default.twig",
            $partsKitPageContent
        );
    }
}