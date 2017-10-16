<?php

namespace hrupin\reviews\widgets;

use Yii;
use yii\base\Widget;
use hrupin\reviews\ReviewsAsset;
use hrupin\reviews\models\Reviews as ModelReviews;
use yii\base\InvalidConfigException;

/**
 * Class Reviews
 *
 * @package hrupin\reviews\widgets
 */
class CustomerRating extends Widget
{
    /**
     * @var string
     */
    public $pageIdentifier;

    /**
     * @var string
     */
    public $reviewsIdentifier;

    /**
     * @var string $reviewsView - template view
     */
    public $reviewsView;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'customer-rating';
        }

    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted
     */
    public function run()
    {
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        $tmp = [];
        $criterion = [];
        if(is_array($this->reviewsIdentifier)){
            foreach ($this->reviewsIdentifier as $rI) {
                foreach ($this->pageIdentifier as $item){
                    $t = $model->getCustomerRating($model->find()->getActiveReviewsForPageAndMainLevel($item,$rI));
                    $tmp = array_merge($t, $tmp);
                }
                foreach (Yii::$app->getModule('reviews')->customOptions[$rI] as $keyCustomOption => $customOption) {
                    $criterion[$rI][$keyCustomOption] = [
                        'label' => $customOption['label'],
                        'bad' => 0,
                        'good' => 0
                    ];
                }
            }
            $tmp = array_count_values($tmp);
            foreach ($tmp as $key => $item) {
                $key = explode('|', $key);
                foreach ($this->reviewsIdentifier as $rI) {
                    $data = Yii::$app->getModule('reviews')->customOptions[$rI];
                    if(in_array($key[2], $data[$key[1]]['statistic']['bad'])){
                        $criterion[$rI][$key[1]]['bad']++;
                    }
                    if(in_array($key[2], $data[$key[1]]['statistic']['good'])){
                        $criterion[$rI][$key[1]]['good']++;
                    }
                }
            }
            foreach ($criterion as $key => $item) {
                foreach ($item as $k => $i) {
                    if($i['good'] > 0){
                        $criterion[$key][$k]['statistic'] = ($i['good'] / ($i['good'] + $i['bad'])) * 100;
                    }
                    else{
                        $criterion[$key][$k]['statistic'] = 0;
                    }
                }
            }
            $criterion = $this->mergeArrayStatistics($criterion);
        }
        else{
            foreach ($this->pageIdentifier as $item){
                $t = $model->getCustomerRating($model->find()->getActiveReviewsForPageAndMainLevel($item,$this->reviewsIdentifier));
                $tmp = array_merge($t, $tmp);
            }
            $tmp = array_count_values($tmp);
            foreach (Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier] as $keyCustomOption => $customOption) {
                $criterion[$keyCustomOption] = [
                    'label' => $customOption['label'],
                    'bad' => 0,
                    'good' => 0
                ];
            }
            foreach ($tmp as $key => $item) {
                $data = Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier];
                $key = explode('|', $key);
                if(in_array($key[1], $data[$key[0]]['statistic']['bad'])){
                    $criterion[$key[0]]['bad']++;
                }
                if(in_array($key[1], $data[$key[0]]['statistic']['good'])){
                    $criterion[$key[0]]['good']++;
                }
            }
            foreach ($criterion as $key => $item) {
                if($item['good'] > 0){
                    $criterion[$key]['statistic'] = ($item['good'] / ($item['good'] + $item['bad'])) * 100;
                }
                else{
                    $criterion[$key]['statistic'] = 0;
                }

            }
        }
        return $this->render($this->reviewsView,[
            'criterion' => $criterion,
            'count' => $model->countAllReviews($this->pageIdentifier,$this->reviewsIdentifier)
        ]);
    }

    private function mergeArrayStatistics($ar){
        $res = [];
        foreach ($ar as $item) {
            foreach ($item as $i) {
                $check = true;
                foreach ($res as $k => $re) {
                    if($re['label'] === $i['label']){
                        $check = false;
                        $res[$k]['bad'] += $i['bad'];
                        $res[$k]['good'] += $i['good'];
                        $res[$k]['statistic'] = ($re['statistic'] + $i['good']) / 2;
                    }
                }
                if($check){
                    $res[] = $i;
                }
            }
        }
        return $res;
    }

}