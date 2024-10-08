<?php

namespace app\controllers;

use Yii;
use app\models\Parameter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\helpers\Translit;

class ParameterController extends Controller
{
    private function findParameterById($id)
    {
        if (($parameter = Parameter::findOne($id)) === null) {
            throw new Error('Не смог найти нужный параметр');
        }
        return $parameter;
    }

    public function actionIndex()
    {
        $queryParameter = new \app\models\ParameterSearch();
        $dataProvider = $queryParameter->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider', 'queryParameter'));
    }

    // Возможность добавить одно или два изображения к параметру.
    public function actionCreate()
    {
        $parameter = new Parameter();
        $this->_saveImages($parameter);

        if ($parameter->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', compact('parameter'));
    }

    // Возможность заменить загруженные изображения на другие.
    public function actionUpdate($ID)
    {
        $parameter = $this->findParameterById($ID);
        $this->_saveImages($parameter);

        if ($parameter->save()) {
            return $this->render('update', compact('parameter'));
        }

        return $this->render('update', compact('parameter'));
    }

    public function actionDeleteimage($parameterId, $imageType)
    {
        $imagesWeCanDelete = [
            'icon' => ['fieldIcon' => 'icon', 'fieldIconOGName' => 'icon_original_name'],
            'icon_gray' => ['fieldIcon' => 'icon_gray', 'fieldIconOGName' => 'icon_gray_original_name'],
        ];
        if(empty($type = $imagesWeCanDelete[$imageType])) {
            throw new Error('Неизвестный тип изображения');
        }
        $parameter = $this->findParameterById($parameterId);
        $iconPath = $parameter->{$type['fieldIcon']};
        $parameter->{$type['fieldIcon']} = null;
        $parameter->{$type['fieldIconOGName']} = null;
        if($parameter->save()){
            unlink($iconPath);
        }

        return $this->redirect(['update', 'ID' => $parameterId]);
    }

    // Реализовать API в котором можно получить все параметры к которым можно подгрузить картинки со списком подгруженных картинок в формате json. Список подгруженных картинок должен иметь исходное имя, путь для просмотра картинок и отметку для понимания что есть icon, а что icon_gray.

    public function actionApiiconsindex()
    {
        $parameters = Parameter::find()->where(['type' => 2])->all();

        $url = Url::base('https') . '/';
        $res = array_reduce($parameters, function($res, $parameter) use($url) {
            return [
                ...$res,
              [
                  'ID' => $parameter->ID,
                  'title' => $parameter->title,
                  'icon_info' => $parameter->icon ? [
                      'original_name' => $parameter->icon_original_name,
                      'url' => $url . $parameter->icon,
                  ] : [],
                  'icon_gray_info' => $parameter->icon_gray ? [
                      'original_name' => $parameter->icon_gray_original_name,
                      'url' => $url . $parameter->icon_gray,
                  ] : [],
              ]
            ];

        }, []);
        return $this->asJson($res);

    }


    private function _saveImages($parameter)
    {
        if ($parameter->load(Yii::$app->request->post())) {
            if($parameter->type == 2){
                $imagesWeCanSave = [
                    ['attribute' => 'FILEIcon', 'tableFieldUrl' => 'icon', 'tableFieldOriginalName' => 'icon_original_name'],
                    ['attribute' => 'FILEIconGray', 'tableFieldUrl' => 'icon_gray', 'tableFieldOriginalName' => 'icon_gray_original_name'],
                ];

                foreach ($imagesWeCanSave as $image) {
                    $icon = UploadedFile::getInstance($parameter, $image['attribute']);
                    if ($icon) {
                        $parameter->{$image['tableFieldUrl']} = $this->_saveImageAsFile($icon);
                        $parameter->{$image['tableFieldOriginalName']} = $icon->name;
                    }
                }
            }
        }

        return null;

    }

    private function _saveImageAsFile($image): string | null
    {
        $imageName = $imageNameOriginal = Translit::translit($image->baseName);
        if(strlen($imageName) > 100) {
            $imageName = substr($imageName, 0, 100);
        }
        $imagePath = 'uploads/' . $imageName . '.' . $image->extension;
        // Баг который был на ларавел решен так
        while (file_exists($imagePath)) {
            $imageName = $imageNameOriginal . time();
            $imagePath = 'uploads/' . $imageName . '.' . $image->extension;
        }


        if (!$image->saveAs($imagePath)) {
            return null;
        }

        return $imagePath;
    }
}