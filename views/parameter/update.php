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
    echo $form->field($parameter, 'FILEIconGray')->fileInput();
}



echo Html::submitButton('Обновить', ['class' => 'btn btn-primary']);

ActiveForm::end();

?>