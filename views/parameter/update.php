<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Parameter;
$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'id' => 'parameter-form',
]);

echo $form->field($parameter, 'title')->textInput();

echo $form->field($parameter, 'type')->dropDownList([1 => 'Type 1', 2 => 'Type 2']);

if($parameter->type == 2)
{
    echo $form->field($parameter, 'FILEIcon')->fileInput();
    if($parameter->icon){
        // ['update', 'ID' => $model->ID]
        // actionDeleteImage($parameterId, $imageType)
        echo Html::img($parameter->icon, ['width' => '200px']);
        echo Html::a('Удалить', ['deleteimage', 'parameterId' => $parameter->ID, 'imageType' => 'icon'], ['class' => 'btn btn-danger']);
    }
    echo $form->field($parameter, 'FILEIconGray')->fileInput();
    if($parameter->icon_gray){
        // ['update', 'ID' => $model->ID]
        // actionDeleteImage($parameterId, $imageType)
        echo Html::img($parameter->icon_gray, ['width' => '200px']);
        echo Html::a('Удалить', ['deleteimage', 'parameterId' => $parameter->ID, 'imageType' => 'icon_gray'], ['class' => 'btn btn-danger']);
    }
}


echo "<br><br>";
echo Html::submitButton('Обновить', ['class' => 'btn btn-primary']);

ActiveForm::end();

?>