<?php

namespace hrupin\reviews\controllers;

use Yii;
use hrupin\reviews\models\Reviews as ModelReviews;

class ReviewsController extends \yii\web\Controller
{

    public function actionCreateReview(){
        if(Yii::$app->request->isAjax){
            $model = Yii::createObject(ModelReviews::className());
            if(Yii::$app->request->isPost){
                if($model->load(Yii::$app->request->post())){
                    $model->user_id = Yii::$app->user->id;
                    $model->dataAr = $model->data;
                    if($model->save()){
                        return $this->renderAjax('response', [
                            'result' => 'success'
                        ]);
                    }
                }
            }
            return $this->renderAjax('response', [
                'result' => 'error'
            ]);
        }
        else{
            echo "This url only AJAX!!!";
        }
    }

    public function actionCreateResponse(){
//        if(Yii::$app->request->isAjax){
//            $model = Yii::createObject(ModelReviews::className());
//            if(Yii::$app->request->isPost){
//                if($model->load(Yii::$app->request->post())){
//                    $model->user_id = Yii::$app->user->id;
//                    $model->dataAr = $model->data;
//                    if($model->save()){
//                        return $this->renderAjax('response', [
//                            'result' => 'success'
//                        ]);
//                    }
//                }
//            }
//            return $this->renderAjax('response', [
//                'result' => 'error'
//            ]);
//        }
//        else{
            echo "This url only AJAX!!!";
//        }
    }

    public function actionUpdateReview(){
//        if(Yii::$app->request->isAjax){
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return ['answer' => '200', 'result' => Category::getChild(Yii::$app->request->post('url'))];
//        }
    }

    public function actionDeleteReview(){
//        if(Yii::$app->request->isAjax){
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return ['answer' => '200', 'result' => Category::getChild(Yii::$app->request->post('url'))];
//        }
    }

}