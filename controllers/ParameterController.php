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

        $this->_saveImages($parameter);

        return $this->render('create', compact('parameter'));
    }

    // Возможность заменить загруженные изображения на другие.
    public function actionUpdate($id)
    {
        $parameter = $this->findParameterById($id);
        $this->_saveImages($parameter);

        return $this->render('update', compact('parameter'));
    }

    public function actionDeleteImage($parameterId, $imageType)
    {
        $imagesWeCanDelete = [
            'icon' => ['fieldIcon' => 'icon', 'fieldIconOGName' => 'icon_original_name'],
            'icon_gray' => ['attribute' => 'icon_gray', 'fieldIconOGName' => 'icon_gray_original_name'],
        ];
        if(empty($type = $imagesWeCanDelete[$imageType])) {
            throw new Error('Неизвестный тип изображения');
        }

        $parameter = $this->findParameterById($parameterId);
        $parameter->{$type['fieldIcon']} = null;
        $parameter->{$type['fieldIconOGName']} = null;
        // TODO удалить изображение с сервера
        $parameter->save();
        return $this->redirect(['update', 'id' => $parameterId]);
    }


    private function _saveImages($parameter)
    {
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
        return null;

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