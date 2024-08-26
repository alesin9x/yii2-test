<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Parameters';
?>

<main>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <?= Html::a('Создать параметер', ['create'], ['class' => 'btn btn-link']) ?>
            </div>
        </div>
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $queryParameter,
            'columns' => [
                'ID',
                'title',
                [
                    'attribute' => 'icon',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->icon ? Html::img($model->icon, ['width' => '50']) : null;
                    }
                ],
                'icon_original_name',
                [
                    'attribute' => 'icon_gray',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->icon_gray ? Html::img($model->icon_gray, ['width' => '50']) : null;
                    }
                ],
                'icon_gray_original_name'
            ]
        ])
        ?>
    </div>
</main>
