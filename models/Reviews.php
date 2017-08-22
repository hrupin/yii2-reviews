<?php

namespace hrupin\reviews\models;

use Yii;
// надо use User

/**
 * This is the model class for table "reviews".
 *
 * @property integer $reviews_id
 * @property string $page
 * @property string $type
 * @property integer $reviews_child
 * @property integer $reviews_parent
 * @property integer $user_id
 * @property integer $level
 * @property integer $raiting
 * @property string $data
 * @property string $text
 * @property integer $date_create
 * @property integer $date_update
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
            [['page', 'type', 'reviews_parent', 'date_create', 'date_update'], 'required'],
            [['reviews_child', 'reviews_parent', 'user_id', 'level', 'raiting', 'date_create', 'date_update'], 'integer'],
            [['data', 'text'], 'string'],
            [['page', 'type'], 'string', 'max' => 20], ['level', 'default', 'value' => 1],
            ['reviews_parent', 'default', 'value' => 0],
            ['reviews_child', 'default', 'value' => false],
            ['date_create', 'default', 'value' => time()],
            ['date_update', 'default', 'value' => time()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reviews_id' => Yii::t('reviews', 'Reviews ID'),
            'page' => Yii::t('reviews', 'Page'),
            'type' => Yii::t('reviews', 'Type'),
            'reviews_child' => Yii::t('reviews', 'Reviews Child'),
            'reviews_parent' => Yii::t('reviews', 'Reviews Parent'),
            'user_id' => Yii::t('reviews', 'User ID'),
            'level' => Yii::t('reviews', 'Level'),
            'raiting' => Yii::t('reviews', 'Raiting'),
            'data' => Yii::t('reviews', 'Data'),
            'text' => Yii::t('reviews', 'Text'),
            'date_create' => Yii::t('reviews', 'Date Create'),
            'date_update' => Yii::t('reviews', 'Date Update'),
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

    public function getStructure()
    {
        return;
    }

}