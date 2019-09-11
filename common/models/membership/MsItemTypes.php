<?php

namespace common\models\membership;

use Yii;

/**
 * This is the model class for table "ms_item_types".
 *
 * @property integer $id
 * @property string $title
 * @property string $key
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_at
 * @property string $modified_by
 * @property integer $is_deleted
 *
 * @property MsItems[] $msItems
 */
class MsItemTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ms_item_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'key'], 'required'],
            [['created_at', 'created_by', 'modified_at', 'modified_by', 'is_deleted'], 'integer'],
            [['title', 'key'], 'string', 'max' => 128],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'key' => 'Key',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMsItems()
    {
        return $this->hasMany(MsItems::className(), ['item_type_id' => 'id']);
    }
}
