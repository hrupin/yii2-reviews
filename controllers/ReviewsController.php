<?php

namespace hrupin\reviews\controllers;

use Yii;
use hrupin\reviews\models\Reviews;

class ReviewsController extends \yii\web\Controller
{

    public function actionAddReview(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        if(Yii::$app->request->isAjax){
//            return ['answer' => '200', 'result' => Category::getChild(Yii::$app->request->post('url'))];
//        }
//        return ['answer' => '100', 'result' => 'not found'];
    }
}