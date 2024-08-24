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
        if (($parameter = Parameter::findOne($id)) === null) {
            throw new Error('Не смог найти нужный параметр');
        }
        return $parameter;
    }

    // Возможность добавить одно или два изображения к параметру.
    public function actionCreate()
    {
        $parameter = new Parameter();

        if ($parameter->load(Yii::$app->request->post())) {
            $imagesWeCanSave = [
                ['attribute' => 'FileIcon', 'tableFieldUrl' => 'icon', 'tableFieldOriginalName' => 'icon_original_name'],
                ['attribute' => 'FileIconGray', 'tableFieldUrl' => 'icon_gray', 'tableFieldOriginalName' => 'icon_gray_original_name'],
            ];

            foreach ($imagesWeCanSave as $image) {
                $icon = UploadedFile::getInstance($parameter, $image['attribute']);
                if ($icon) {
                    $parameter->{$image['tableFieldUrl']} = $this->_saveImageAsFile($icon);
                    $parameter->{$image['tableFieldOriginalName']} = $icon->name;
                }
            }

            if ($parameter->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', compact('parameter'));
    }

    private function _saveImageAsFile($image): string | null
    {
        $imageNameOriginal = $image->name;
        $imagePath = Yii::getAlias('@webroot/uploads/') . $imageNameOriginal . '.' . $image->extension;
        // Баг который был на ларавел решен так
        while (file_exists($imagePath)) {
            $imageName = $imageNameOriginal . time();
            $imagePath = Yii::getAlias('@webroot/uploads/') . $imageName . '.' . $image->extension;
        }

        if (!$image->saveAs($imagePath)) {
            return null;
        }

        return $imagePath;
    }
}