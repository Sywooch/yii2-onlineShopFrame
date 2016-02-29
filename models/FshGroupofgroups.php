<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fsh_groupofgroups".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property string $slug
 * @property string $icon
 */
class FshGroupofgroups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fsh_groupofgroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'icon'], 'required'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 60],
            [['icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sort' => 'Sort',
            'slug' => 'Slug',
            'icon' => 'Icon',
        ];
    }
}
