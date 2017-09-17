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
            $tmpAr = $model->getCustomerRating($model->find()->getActiveReviewsForPageAndMainLevel(
                    $item,
                    $this->reviewsIdentifier
                )
            );
            $tmp = ModelReviews::array_custom_merge($tmp, $tmpAr);
        }
        $result = $tmp;
        $criterion = [];
        foreach (Yii::$app->getModule('reviews')->customOptions as $key => $item) {
            if($key === $this->reviewsIdentifier){
                foreach ($item as $keyItem => $valueItem) {
                    $criterion[$keyItem] =$valueItem['label'];
                }
            }
        }
        return $this->render($this->reviewsView,[
            'model' => $result,
            'criterion' => $criterion
        ]);
    }
}
