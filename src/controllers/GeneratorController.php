<?php

namespace viget\generator\controllers;

use craft\console\Controller;
use yii\console\ExitCode;

class GeneratorController extends Controller
{
    public function actionDoThing(): int
    {
        return ExitCode::OK;
    }
}