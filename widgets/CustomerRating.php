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
        foreach ($this->pageIdentifier as $item){
            $t = $model->getCustomerRating($model->find()->getActiveReviewsForPageAndMainLevel($item,$this->reviewsIdentifier));
            $tmp = array_merge($t, $tmp);
        }
        $tmp = array_count_values($tmp);
        $criterion = [];
        foreach (Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier] as $keyCustomOption => $customOption) {
            $criterion[$keyCustomOption] = [
                'label' => $customOption['label'],
                'bad' => 0,
                'good' => 0,
                'count' => 0
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
            $criterion[$key[0]]['count']++;
        }
        foreach ($criterion as $key => $item) {
            $criterion[$key]['statistic'] = ($item['good'] / ($item['good'] + $item['bad'])) * 100;
        }
        return $this->render($this->reviewsView,[
            'criterion' => $criterion,
            'count' => $model->countAllReviews($this->pageIdentifier,$this->reviewsIdentifier)
        ]);
    }
}
