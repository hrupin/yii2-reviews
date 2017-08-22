<?php

namespace hrupin\reviews\models;

/**
 * This is the ActiveQuery class for [[Reviews]].
 *
 * @see Reviews
 */
class ReviewsQuery extends \yii\db\ActiveQuery
{

    public function getReviewsForPage($id)
    {
        return $this->andWhere('[[reviews_id]]='.$id);
    }

    /**
     * @inheritdoc
     * @return Reviews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Reviews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}