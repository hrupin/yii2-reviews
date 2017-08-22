<?php
namespace hrupin\reviews\controllers;
use hrupin\reviews\models\Reviews;
use hrupin\reviews\models\ReviewsSearch;
use yii\base\Model;
use Yii;
use yii\base\Security;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    // вот такое переопределить нельзя - хардкод
//$model = new Model();
    // а вот такое переопределить можно с помощью контейнера
//$model = Yii::createObject(Model::className());
    public function actionIndex(){
        $searchModel = new ReviewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $aP = $searchModel->getIdParent();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'langs' => $this->module->lang,
            'aP' => $aP
        ]);
    }
    public function actionAddCategory(){
        $categories = [];
        foreach ($this->module->lang as $key=>$value){
            $categories[$key] = new Reviews();
            $categories[$key]->loadDefaultValues($key);
        }
        if (Model::loadMultiple($categories, Yii::$app->request->post()) && Model::validateMultiple($categories)) {
            foreach ($categories as $category) {
                $category->save();
            }
            return $this->redirect(Url::toRoute('/blog/admin/index'));
        }
        return $this->renderAjax('add-category',[
            'categories' => $categories,
            'lang' => $this->module->lang
        ]);
    }
    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
//    public function actionIndex(){
//        return $this->render('index');
//    }
//
//    public function actionIndex(){
//        return $this->render('index');
//    }
//
//    public function actionIndex(){
//        return $this->render('index');
//    }
//
//    public function actionIndex(){
//        return $this->render('index');
//    }
}