<?php

namespace frontend\controllers;

use Yii;
use common\models\Post;
use common\models\Tag;
use common\models\User;
use common\models\Comment;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public $added = 0;
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

            'access' =>[
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => ['index'],
                            'allow' => true,
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
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $tags = Tag::findTagWidget();
        $comments = Comment::findRecentComments();
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tags' => $tags,
            'comments' => $comments,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
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
     * Deletes an existing Post model.
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
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //文章详情页
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        $tags = Tag::findTagWidget();
        $comments = Comment::findRecentComments();
        $user = User::findOne(Yii::$app->user->id);
        $commentModel = new Comment();
        $commentModel->email = $user->email;
        $commentModel->userid = $user->id;

        if($commentModel->load(Yii::$app->request->post()))
        {
            $commentModel->status = 1;
            $commentModel->post_id = $id;
//            echo "<pre>";
//            var_dump($commentModel);die;
            if($commentModel->save())
            {
                $this->added = 1;
            }
        }
        return $this->render('detail',[
            'model'=>$model,
            'tags'=>$tags,
            'comments'=>$comments,
            'added'=>$this->added,
            'commentModel'=>$commentModel,
        ]);
    }
}
