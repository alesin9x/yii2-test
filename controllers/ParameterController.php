<?php

namespace app\controllers;

use Yii;
use app\models\Parameter;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use app\components\Transliterator;

class ParametersController extends Controller
{
    private function findParameterById($id)
    {
        if( ($parameter = Parameter::findOne($id)) === null) {
            throw new Error('Не смог найти нужный параметр');
        }
        return $parameter;
    }
}