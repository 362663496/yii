<?php

namespace backend\controllers;

use backend\models\SignupForm;
use backend\models\ResetForm;
use Yii;
use common\models\Adminuser;
use common\models\AuthItem;
use common\models\AuthAssignment;
use common\models\AdminuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * AdminuserController implements the CRUD actions for Adminuser model.
 */
class AdminuserController extends Controller
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

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Adminuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Adminuser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Adminuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if($user = $model->signup())
            {
                return $this->redirect(['view', 'id' => $user->id]);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionResetpwd($id)
    {
        $model = new ResetForm();

        if ($model->load(Yii::$app->request->post())) {
            if($model->resetpassword($id))
            {
                return $this->redirect(['index']);
            }

        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Adminuser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Adminuser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Adminuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Adminuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Adminuser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrivilege($id)
    {
        if(!Yii::$app->user->can('privilege')){
            throw new ForbiddenHttpException('您没有权限进行该操作！');
        }

        //所有的权限 提供给checkbox
        $allItems = AuthItem::find()->select(['name','description'])->where(['type'=>1])->orderBy('description')->all();
        $allArr = [];
        foreach($allItems as $v)
        {
            $allArr[$v->name] = $v->description;
        }
//        echo "<pre>";
//        var_dump($allArr);

        //当前用户权限
        $userItems = AuthAssignment::find()->select(['item_name'])->where(['user_id'=>$id])->all();
        $userArr = [];
        foreach($userItems as $v)
        {
           array_push($userArr,$v->item_name);
        }
//        echo "<hr>";
//        var_dump($userArr);

        //设置权限
        if(isset($_POST['newPri']))
        {
//            echo "<pre>";
//            var_dump($_POST['newPri']);die;
            AuthAssignment::deleteAll('user_id=:id',[':id'=>$id]);
            foreach($_POST['newPri'] as $v)
            {
                $item = new AuthAssignment();
                $item->item_name = $v;
                $item->user_id = $id;
                $item->created_at = time();
                $item->save();
            }
            return $this->redirect(['index']);
        }


        return $this->render('privilege',['id'=>$id,'allArr'=>$allArr,'userArr'=>$userArr]);

    }
}
