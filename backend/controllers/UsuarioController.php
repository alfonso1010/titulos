<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\UserSearch;
use yii\data\ActiveDataProvider;
use common\models\AuthAssignment;

/**
 * Site controller
 */
class UsuarioController extends Controller
{
    
     /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];
        
        $behaviors['access'] = [
            'class' => \yii\filters\AccessControl::className(),
            'rules' => [
                \common\helpers\UtilidadesHelper::behaviorRbac()
            ],
        ];
        return $behaviors;
    }

    
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel  = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $model->load($data);
            $bandera = true;
            
            if($data['tipoUsuario'] == "universidad"){
                $instituciones = Yii::$app->request->post('instituciones');
                $instituciones_str = "";
                if (!is_null($instituciones)) {
                    foreach ($instituciones as $key => $value) {
                        $instituciones_str .= $value.",";
                    }
                    $model->instituciones = trim($instituciones_str,","); 
                } else {
                    $bandera = false;
                    Yii::$app->session->setFlash(
                        'error', 'Debe seleccionar una institucion al usuario.'
                    );
                }
            }
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
            $model->status = 10;
            $rol = $data['tipoUsuario'];
            if(preg_match('/\s/',$model->username)){
                $model->password_hash = "";
                $bandera = false;
                Yii::$app->session->setFlash(
                    'error', 'Username no debe tener espacios en blanco.'
                );
            }
            if($data['tipoUsuario'] == ""){
                $model->password_hash = "";
                $bandera = false;
                Yii::$app->session->setFlash(
                    'error', 'Debe seleccionar un tipo de usuario.'
                );
            }
            if($bandera && $model->save()){
                User::asignarol($model->id, $rol);
                Yii::$app->session->setFlash(
                    'success', 'usuario creado con éxito'
                );
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $model->load($data);
            $bandera = true;
            if($data['tipoUsuario'] == "universidad"){
                $instituciones = Yii::$app->request->post('instituciones');
                $instituciones_str = "";
                if (!is_null($instituciones)) {
                    foreach ($instituciones as $key => $value) {
                        $instituciones_str .= $value.",";
                    }
                    $model->instituciones = trim($instituciones_str,","); 
                } else {
                    $bandera = false;
                    Yii::$app->session->setFlash(
                        'error', 'Debe seleccionar una institucion al usuario.'
                    );
                }
            }
            $model->setPassword($model->password_hash);
            $model->generateAuthKey();
            $model->status = 10;
            $rol = $data['tipoUsuario'];
            if($bandera && $model->save()){
                User::asignarol($model->id, $rol);
                Yii::$app->session->setFlash(
                    'success', 'usuario actualizado con éxito'
                );
                return $this->redirect(['index']);
            }
        }
        $model->password_hash = "";

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $rol = AuthAssignment::findOne(['user_id' => $model->id]);
        $roln = (!is_null($rol))?$rol->item_name:"No asignado";
        if($roln == "admin"){
            Yii::$app->session->setFlash(
                'error', 'No se pueden eliminar usuarios administradores'
            );
            return $this->redirect(['index']);
        }else{
            if(!is_null($rol)){
                $rol->delete();
            }
            $model->delete();
            Yii::$app->session->setFlash(
                'success', 'usuario eliminado con éxito'
            );
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

   
}
