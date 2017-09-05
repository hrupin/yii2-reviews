<?php
namespace hrupin\reviews\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use hrupin\reviews\models\Reviews;

class AdminController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Reviews models.
     * @return mixed
     */
    public function actionIndex()
    {
        $class = Yii::$app->getModule('reviews')->modelMap['ReviewsSearch'];
        $searchModel = Yii::createObject($class::className());
        $tmpQuery = Reviews::find()->select(['page', 'type'], 'DISTINCT');
        if(Yii::$app->request->get('type')){
            $tmpQuery->andWhere(['type' => Yii::$app->request->get('type')]);
        }
        $pageAndType = $tmpQuery->all();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $types = [];
        foreach (Reviews::find()->select(['type'], 'DISTINCT')->all() as $item) {
            $types[$item->type] = Reviews::find()->getNoActiveReviewsForPage($item->type)->count();
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'types'        => $types,
            'pageAndType' => $pageAndType
        ]);
    }

    /**
     * Displays a single Reviews model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Reviews model.
     * @param string $page
     * @param string $type
     * @return mixed
     */
    public function actionViewReview($page, $type)
    {
        $ratingStars = Yii::$app->getModule('reviews')->ratingStars;
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        $model->rating = $model->getAverageNumberStars($page, $type);
        $model->type = $type;
        $model->page = $page;
        $reviews = $model->getReviews(Reviews::find()->getAllReviewsForPageAndMainLevel($page, $type));
        return $this->render('view-reviews',[
            'reviews' => $reviews,
            'model' => $model,
            'pathIMG' => '',
            'stars' => $ratingStars,
            'options' => Yii::$app->getModule('reviews')->customOptions[$type],
        ]);
    }

    /**
     * Creates a new Reviews model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reviews_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Reviews model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reviews_id]);
        } else {
            $ratingStars = Yii::$app->getModule('reviews')->ratingStars;
            $model->data = $model->dataAr;
            return $this->render('update',[
                'model' => $model,
                'pathIMG' => '',
                'stars' => $ratingStars,
                'options' => Yii::$app->getModule('reviews')->customOptions[$model->type]
            ]);

        }
    }

    /**
     * Deletes an existing Reviews model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $class = \Yii::$app->getModule('reviews')->modelMap['Reviews'];
            $model = Yii::createObject($class::className());
            if(Yii::$app->request->isPost) {
                if (Yii::$app->request->post('reviews_id')) {
                    if($data = $model->find()->getReviews(Yii::$app->request->post('reviews_id'))){
                        $data->status = 99;
                        if($data->save()){
                            $res = [
                                'status' => 'success',
                                'reload' => (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1,
                                'message' => "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Success!</strong> Opinion deleted successfully.')."</div>"
                            ];
                            return json_encode($res);
                        }
                    }
                }
            }
            $res = [
                'status' => 'error',
                'message' => "<div class='alert alert-danger'>".Yii::t('reviews', '<strong>Error!</strong> The opinion was not sent! Repeat again after some time.')."</div>"
            ];
            return json_encode($res);
        }
        echo "This url only AJAX!!!";
    }

    /**
     * Finds the Reviews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reviews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}