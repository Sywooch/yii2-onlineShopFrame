<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fsh_groupofcat".
 *
 * @property integer $id
 * @property string $name
 * @property integer $topgroup_id
 * @property integer $sort
 * @property string $slug
 * @property string $icon
 */
class FshGroupofcat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fsh_groupofcat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'icon'], 'required'],
            [['topgroup_id', 'sort'], 'integer'],
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
            'topgroup_id' => 'Topgroup ID',
            'sort' => 'Sort',
            'slug' => 'Slug',
            'icon' => 'Icon',
        ];
    }
}
