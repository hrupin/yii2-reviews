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
class CountAnswer extends Widget
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
     * @var string $reviewsLabel - name reviewsIdentifier
     */
    public $reviewsLabel;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }
        if (!is_array($this->pageIdentifier)) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be array.'));
        }

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'count-answer';
        }

        if ($this->reviewsLabel === null) {
            $this->reviewsLabel = [];
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
        $res = [];
        if(is_array($this->reviewsIdentifier)){
            foreach ($this->reviewsIdentifier as $item) {
                $res[$item] = Yii::$app->getModule('reviews')->customOptions[$item];
            }
        }
        else{
            $res[$this->reviewsIdentifier] = Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier];
        }
        foreach ($this->pageIdentifier as $k => $i){
            foreach ($res as $kk => $ii) {
                foreach ($model->find()->where(['page' => $i,'type' => $kk])->all() as $kkk => $iii) {
                    foreach ($iii->dataAr as $kkkk => $iiii){
                        if(!isset($res[$kk][$kkkk]['count'])){
                            foreach ($res[$kk][$kkkk]['data'] as $kkkkk => $iiiii) {
                                $res[$kk][$kkkk]['count'][$kkkkk] = 0;
                            }
                        }
                        $res[$kk][$kkkk]['count'][$iiii]++;
                    }
                }
            }
        }
        return $this->render($this->reviewsView,[
            'data' => $res,
            'label' => $this->reviewsLabel
        ]);
    }
}