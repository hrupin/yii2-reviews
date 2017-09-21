<?php

namespace hrupin\reviews\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "reviews".
 *
 * @property integer $reviews_id
 * @property string $page
 * @property string $type
 * @property integer $status
 * @property integer $reviews_child
 * @property integer $reviews_parent
 * @property integer $user_id
 * @property integer $level
 * @property integer $rating
 * @property string $data
 * @property string $text
 * @property integer $date_create
 * @property integer $date_update
 */
class Reviews extends \yii\db\ActiveRecord
{

    const REVIEWS_ACTIVE = 1;
    const REVIEWS_NOT_ACTIVE = 0;
    const REVIEWS_DELETE = 99;

    public static $pathIMG;
    public static $html;
    public static $email = false;

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
            [['page', 'type', 'text', 'rating'], 'required'],
            [['reviews_child', 'reviews_parent', 'user_id', 'level', 'rating', 'date_create', 'date_update'], 'integer'],
            [['data', 'text'], 'required', 'message' => Yii::t('reviews', 'Element cannot be blank.')],
            [['page', 'type'], 'string', 'max' => 60], ['level', 'default', 'value' => 1],
            ['reviews_parent', 'default', 'value' => 0],
            ['reviews_child', 'default', 'value' => false],
            ['date_create', 'default', 'value' => time()],
            ['date_update', 'default', 'value' => time()],
            ['rating', 'integer', 'min' => 1]
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
            'status' => Yii::t('reviews', 'Status'),
            'reviews_child' => Yii::t('reviews', 'Reviews Child'),
            'reviews_parent' => Yii::t('reviews', 'Reviews Parent'),
            'user_id' => Yii::t('reviews', 'User ID'),
            'level' => Yii::t('reviews', 'Level'),
            'rating' => Yii::t('reviews', 'Rating'),
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->text = self::purifier($this->text);
            $this->date_update = time();

            return true;
        }
        return false;
    }

    public static function purifier($text)
    {
        $pr = new HtmlPurifier;
        return $pr->process($text);
    }

    public function getUser()
    {
        $class = Yii::$app->getModule('reviews')->userModel;
        return $this->hasOne($class::className(), ['id' => 'user_id']);
    }

    public function getDataAr()
    {
        return unserialize($this->data);
    }

    public function setDataAr()
    {
        if($this->data !== null){
            $this->data = serialize($this->data);
        }
        else{
            $this->data = serialize([]);
        }
    }

    public function getDateReviews()
    {
        return date('d.m.Y', $this->date_update);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['reviews_id' => 'reviews_parent']);
    }

    public function getChildren()
    {
        if(array_search(Yii::$app->user->id, Yii::$app->getModule('reviews')->admin) !== false){
            return $this->hasMany(self::className(), ['reviews_parent' => 'reviews_id'])->andOnCondition('status <>' . Reviews::REVIEWS_DELETE);
        }
        return $this->hasMany(self::className(), ['reviews_parent' => 'reviews_id'])->andOnCondition(['status' => Reviews::REVIEWS_ACTIVE]);
    }

    public function getAverageNumberStars($id, $type){
        $res = 0; $stars = 0;
        foreach ($this->find()->getActiveReviewsForPage($id, $type)->all() as $item) {
            $stars += $item->rating;
            $res++;
        }
        if($res){
            return $stars / $res;
        }
        else{
            return 1;
        }
    }

    public function getReviews($arReviews){
        $result = [];
        $step = 0;
        foreach($arReviews as $key => $value) {
            $result[$step] =  $value->dataReview;
            if($value->reviews_child){
                $result[$step]['children'] = $this->getReviews($value->children);
            }
            $step++;
        }
        return $result;
    }

    /**
     * @param $obj /hrupin/reviews/model/Reviews
     * @return array
     */
    public function getDataReview(){
        return [
            'idReviews' => $this->reviews_id,
            'text'      => $this->text,
            'user_id'   => $this->user_id,
            'date'      => $this->dateReviews,
            'name'      => $this->user->publicName,
            'rating'    => $this->rating,
            'img'       => ($this->user->publicAvatar)? $this->user->publicAvatar: $this::$pathIMG.'/img/noAvatar.jpg',
            'level'     => $this->level,
            'parent'    => $this->reviews_parent,
            'status'    => $this->status,
            'data'      => $this->dataAr
        ];
    }

    public static function generateHTML($template, $data, $tagMain, $tag, $level){
        self::$html .= '<'.$tagMain.' style="margin-left: '.($level + 1).'%;">';
        foreach ($data as $value){
            $stars = '';
            $delete = '';
            $edit = '';
            $success = '';
            $notActive = '';
            if(array_search(Yii::$app->user->id, Yii::$app->getModule('reviews')->admin) !== false && $value['status'] == Reviews::REVIEWS_NOT_ACTIVE){
                $notActive = 'newReview';
                $success = '<span class="success" data-id="'.$value['idReviews'].'"><small>'.Yii::t('reviews', 'Success review').'</small></span>';
            }
            if(Yii::$app->user->id == $value['user_id'] || array_search(Yii::$app->user->id, Yii::$app->getModule('reviews')->admin) !== false){
                $delete = '<span class="delete" data-id="'.$value['idReviews'].'"><small>'.Yii::t('reviews', 'Delete review').'</small></span>';
                $edit = '<span class="edit" data-id="'.$value['idReviews'].'"><small>'.Yii::t('reviews', 'Edit review').'</small></span>';
            }
            if($value['level'] == 1){
                for($e = 0; $e < $value['rating']; $e++){
                    $stars .= '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
                }
                self::$html .= '<'.$tag.' class="clearfix all r_'.$value['rating'].' '.$notActive.'">';
            }
            else{
                self::$html .= '<'.$tag.' class="clearfix '.$notActive.'">';
            }

            $data = '<ul class="dataList">';
            foreach ($value['data'] as $key => $datum) {
                $data .= "<li class='keyData'>".$key."</li>";
                $data .= "<li class='valueData'>".$datum."</li>";
            }
            $data .= '</ul>';
            self::$html .= strtr($template, [
                '{img}'         => $value['img'],
                '{identifier}'  => 'reviews_'.$value['idReviews'],
                '{date}'        => $value['date'],
                '{name}'        => $value['name'],
                '{stars}'       => $stars,
                '{says}'        => Yii::t('reviews', 'says'),
                '{idReviews}'   => $value['idReviews'],
                '{delete}'      => $delete,
                '{edit}'        => $edit,
                '{success}'     => $success,
                '{reply}'       => Yii::t('reviews', 'Reply'),
                '{text}'        => $value['text'],
                '{data}'        => $data
            ]);
            if(isset($value['children'])){
                self::generateHTML($template, $value['children'], $tagMain, $tag, $value['level']++);
            }
            self::$html .= '</'.$tag.'>';
        }
        self::$html .= '</'.$tagMain.'>';
    }

    public function getStatistics($model, $ar){
        $statistics = [];
        foreach ($model as $value){
            foreach ($ar as $k => $v){
                if($v['check'] >= $value->rating){
                    $statistics[] = $v['name'];
                    break;
                }
            }
        }
        $res = array_count_values($statistics);
        foreach ($ar as $k => $v){
            if(!array_key_exists($v['name'], $res)){
                $res[$v['name']] = 0;
            }
        }
        return $res;
    }

    public function getCustomerRating($model){
        $res = [];
        $step = 0;
        foreach ($model as $item) {
            $step++;
            foreach ($item->dataAr as $k => $i) {
                if(!array_key_exists($k, $res)){
                    $res[$k] = [];
                }
                $res[$k][] = $i;
            }
        }
        $result = [];
        foreach ($res as $key => $value){
            $max = max($value);
            $tmp = array_count_values($value);
            $result[$key] = round(($tmp[$max] / $step) * 100);
        }
        return ['res' => $result, 'count' => $step];
    }

    public static function getSecondaryPositiveNumber($id, $type)
    {
        $rating = Yii::$app->getModule('reviews')->ratingStars;
        end($rating);
        $key = key($rating);
        $count = 0;
        $tmpR = 0;
        foreach ($id as $item) {
            $count += Reviews::find()->getActiveReviewsForPageAndMainLevelCount($item, $type);
            $tmpR += Reviews::find()->countActiveReviewsForPageAndMainLevelForRating($item, $type, $key);
        }
        if($tmpR <= 0){
            return [
                'rating' => 0,
                'count' => 0
            ];
        }
        return [
            'rating' => ($tmpR/$count)*100,
            'count' => $count
        ];
    }
    
    public static function getNewReviews(){
        self::find()->getNotActive()->count();
    }

    public static function array_custom_merge($arr1, $arr2){
        $newArr = $arr1;
        foreach($arr2 as $k => $v){
            if(isset($newArr[$k])){
                $newArr[$k] += $v;
            }
            else{
                $newArr[$k] = $v;
            }
        }
        return $newArr;
    }
   
    public static function arraySum($arr1, $arr2) {
        $result = [];
        foreach($arr1 as $val) {
            $result[] = $val;
        }
        foreach($arr2 as $val) { // считываем 2-ой  массив
            $result[] = $val;
        }
        return $result;
    }
    
}
