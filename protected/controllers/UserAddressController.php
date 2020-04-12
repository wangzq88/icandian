<?php

class UserAddressController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/front';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
                'application.filters.FrontFilter'
            ),		
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create','update','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$result = array('success' => false,'info' => '发生未知的错误！请再试一次！');
		$model = new UserAddress;
		$_POST['address'] = trim(strip_tags($_POST['address']));
		$_POST['uid'] = Yii::app()->user->id;
		$model->attributes = $_POST;
		if($model->save()) {
			$result['success'] = $model->id;
			$result['address'] = $model->address;
			$result['direct'] = isset($_POST['direct']) && $_POST['direct'] ? urldecode($_POST['direct']):'';
		}
		header('content-type: application/json; charset=utf-8'); 
		exit(json_encode($result));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['UserAddress']))
		{
			$model->attributes=$_POST['UserAddress'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$id = (int)$_POST['id'];
		$uid = Yii::app()->user->id;
		UserAddress::model()->deleteByPk($id,'uid=:uid',array(':uid'=>$uid));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$connection = Yii::app()->db;
		$uid = Yii::app()->user->id;
		$sql = "SELECT * FROM ".UserAddress::model()->tableName()." WHERE uid=$uid ORDER BY id DESC ";
		$command = $connection->createCommand($sql);
		$rows = $command->queryAll();
		$province_list = Province::model()->getAllProvinces();
		$city_list = City::model()->getAllCities();
		$this->render('index',array(
			'province_list'=>$province_list,	
			'cities' => $city_list,
			'address_list'=>$rows
		));		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserAddress('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserAddress']))
			$model->attributes=$_GET['UserAddress'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=UserAddress::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-address-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
