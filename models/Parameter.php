<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Parameter extends ActiveRecord
{
    public $FILEIcon;
    public $FILEIconGray;

    public static function tableName()
    {
        return 'parameters';
    }

    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['type'], 'integer'],
            [['FILEIcon', 'FILEIconGray'],'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png'],
        ];
    }

}
