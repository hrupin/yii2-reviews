<?php

namespace hrupin\reviews\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property integer $reviews_id
 * @property string $type
 * @property integer $reviews_child
 * @property integer $reviews_parent
 * @property integer $user_id
 * @property integer $level
 * @property integer $raiting
 * @property string $data
 * @property string $text
 * @property integer $data_create
 * @property integer $data_update
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'raiting', 'data', 'text', 'data_create', 'data_update'], 'required'],
            [['reviews_child', 'user_id', 'raiting'], 'integer'],
            [['data', 'text'], 'string'],
            [['type'], 'string', 'max' => 50],
            ['level', 'default', 'value' => 1],
            ['reviews_parent', 'default', 'value' => 0],
            ['reviews_child', 'default', 'value' => false],
            ['data_create', 'default', 'value' => time()],
            ['data_update', 'default', 'value' => time()],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reviews_id' => 'Reviews ID',
            'type' => 'Type',
            'reviews_child' => 'Reviews Child',
            'reviews_parent' => 'Reviews Parent',
            'user_id' => 'User ID',
            'level' => 'Level',
            'raiting' => 'Raiting',
            'data' => 'Data',
            'text' => 'Text',
            'data_create' => 'Data Create',
            'data_update' => 'Data Update',
        ];
    }

    /**
     * @inheritdoc
     * @return ReviewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ReviewsQuery(get_called_class());
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->data_update = time();
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'users_id']);
    }

    public function getDataAr()
    {
        return unserialize($this->data);
    }

    public function setDataAr($value)
    {
        $this->data = serialize($value);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['reviews_id' => 'reviews_parent']);
    }

    public function getChildren()
    {
        return $this->hasMany(self::className(), ['reviews_parent' => 'reviews_id']);
    }

    public function getStructure(){
        return;
    }

}