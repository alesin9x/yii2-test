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
            [['title', 'icon', 'icon_gray', 'icon_original_name', 'icon_gray_original_name'], 'string', 'max' => 255],
            [['icon_file', 'icon_gray_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png'],
        ];
    }

    private function _getIcon($type = 'icon')
    {
        if(!$this->$type) return null;
        return Yii::$app->request->baseUrl . '/uploads/' . $this->$type;
    }

    public function getIconUrl()
    {
        return $this->_getIcon();
    }

    public function getIconGrayUrl()
    {
        return $this->_getIcon('icon_gray');
    }


}
