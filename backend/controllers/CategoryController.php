<?php

namespace backend\controllers;

use backend\components\AuthController;
use common\models\base\Category;
use common\models\search\CategorySearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends AuthController
{

    public function init()
    {
        $this->view->params['menu'] = 'categories';
        $this->view->params['submenu'] = 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchmodel = new \common\models\search\CategorySearch;
        $query = $searchmodel->search(Yii::$app->request->get());
        $data['pages'] = $query->getPagination();
        $data['dataProvider'] = $query->getModels();

        return $this->render('index', $data);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Cloudinary::config(array(
                "cloud_name" => YII::$app->params['cloudinaryName'],
                "api_key" => YII::$app->params['cloudinaryApiKey'],
                "api_secret" => YII::$app->params['cloudinarySecret'],
            ));

            $model->image = UploadedFile::getInstance($model, 'image');
            if ($filename = $model->upload(Url::to('@uploadpath') . '\\' . 'category' . '\\' . $model->id . '\\', $model->slug)) {

                $model->image = Url::to('/uploads/' . $model->id . '/category' . '/' . $filename['filename'] . $filename['extension']);
                $model->image_path = Url::to('@uploadpath') . '/category' . '/' . $model->id . '/' . $filename['filename'] . $filename['extension'];
                $thumbnail_path = Url::to('@uploadpath') . '/category' . '/' . $model->id . '/' . $filename['filename'] . '-thumb' . $filename['extension'];

                $original_image = \Cloudinary\Uploader::upload($model->image_path);
                $thumbnail_image = \Cloudinary\Uploader::upload($thumbnail_path);

                unlink($thumbnail_path);

                $model->image = $original_image['url'];
                $model->image_thumbnail = $thumbnail_image['url'];

                $model->save(false);
            }
            Yii::$app->session->setFlash('success', 'Category Created');
            return $this->redirect(['/category/']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

/**
 * Updates an existing Category model.
 * If update is successful, the browser will be redirected to the 'view' page.
 * @param integer $id
 * @return mixed
 * @throws NotFoundHttpException if the model cannot be found
 */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();
        if (!isset($_FILES['image'])) {
            $post['Category']['image'] = $model->image;
        }

        if (Yii::$app->request->post() && $model->load($post) && $model->save()) {
            \Cloudinary::config(array(
                "cloud_name" => YII::$app->params['cloudinaryName'],
                "api_key" => YII::$app->params['cloudinaryApiKey'],
                "api_secret" => YII::$app->params['cloudinarySecret'],
            ));
            $image = UploadedFile::getInstance($model, 'image');

            if ($image != null) {
                $model->image = $image;
                if ($filename = $model->upload(Url::to('@uploadpath') . '\\' . 'category' . '\\' . $model->id . '\\', $model->slug)) {
                    $model->image = Url::to('/uploads/' . $model->id . '/category' . '/' . $filename['filename'] . $filename['extension']);
                    $model->image_path = Url::to('@uploadpath') . '/category' . '/' . $model->id . '/' . $filename['filename'] . $filename['extension'];
                    $thumbnail_path = Url::to('@uploadpath') . '/category' . '/' . $model->id . '/' . $filename['filename'] . '-thumb' . $filename['extension'];

                    $original_image = \Cloudinary\Uploader::upload($model->image_path);
                    $thumbnail_image = \Cloudinary\Uploader::upload($thumbnail_path);

                    unlink($thumbnail_path);

                    $model->image = $original_image['url'];
                    $model->image_thumbnail = $thumbnail_image['url'];

                    $model->save(false);
                }
            }

            Yii::$app->session->setFlash('success', 'Category Updated');
            return $this->redirect('/category/');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

/**
 * Deletes an existing Category model.
 * If deletion is successful, the browser will be redirected to the 'index' page.
 * @param integer $id
 * @return mixed
 * @throws NotFoundHttpException if the model cannot be found
 */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = -9;
        $model->save(false);
        Yii::$app->session->setFlash('success', 'Category Deleted');
        return $this->redirect('/category');
    }

/**
 * Finds the Category model based on its primary key value.
 * If the model is not found, a 404 HTTP exception will be thrown.
 * @param integer $id
 * @return Category the loaded model
 * @throws NotFoundHttpException if the model cannot be found
 */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
