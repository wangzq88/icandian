<?php

class FoodCategoriesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/business';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			array(
                'application.filters.BussinessFilter'
            ), // perform access control for CRUD operations
			'postOnly + delete,create,update', // we only allow deletion via POST request
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete'),
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
		$model=new FoodCategories;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FoodCategories']))
		{
			$cat_count = Yii::app()->db->createCommand("SELECT COUNT(*) AS count FROM {{categories}} WHERE shop_id=".Yii::app()->user->shop_id)->queryScalar();
			if ($cat_count > MAX_FOOD_CATEGORY_COUNT) {
				$result ['success'] = false;
				$result ['info'] = '美食分类最多只能添加'.MAX_FOOD_CATEGORY_COUNT.'个';				
			} else {
				$model->shop_id = Yii::app()->user->shop_id;
				$model->categories_name =  strip_tags ($_POST['FoodCategories']['categories_name']);
				$model->categories_description =  strip_tags ($_POST['FoodCategories']['categories_description']);
				$model->ordering = intval($_POST['FoodCategories']['ordering']);
				$model->status = intval($_POST['FoodCategories']['status']);
			//	$model->attributes=$_POST['FoodCategories'];
				$result = array('success' => 0,'info' => '分类名称不能为空'); 
				if($model->save()) {
					$result ['success'] = $model->categories_id ;
					$result ['info'] = '美食分类已经创建成功';
				}
			}
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));						
		}
//		$this->render('create',array(
//			'model'=>$model,
//		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model = $this->loadModel ( (int)$_POST['categories_id'] );
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$result = array ('success' => 0, 'info' => '分类名称不能为空' );
		if ($_POST) {
			//$model->shop_id = (int)$_SESSION['shop_id'];
			//$model->categories_name =  strip_tags ($_POST['categories_name']);
			//$model->categories_description =  strip_tags ($_POST['categories_description']);		
			$model->attributes = $_POST;
			
			if ($model->save ()) {
				//	$model->save();		//$this->redirect(array('view','id'=>$model->categories_id));
				

				$result ['success'] = $model->categories_id;
				$result ['info'] = '菜式分类已经创建成功';
			}
		
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );	
//		$this->render('update',array(
//			'model'=>$model,
//		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$model = $this->loadModel((int)$_POST['categories_id']);
		if($model->shop_id == (int)Yii::app()->user->shop_id)
			$model->delete();
		exit();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
//		$dataProvider=new CActiveDataProvider('FoodCategories');
		if($_POST) {
			$shop_id = Yii::app()->user->shop_id;
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$categories_name = strip_tags($_POST['categories_name']);
			$sql = $categories_name ? " AND categories_name LIKE '%{$categories_name}%'":"";
			
			$command = Yii::app()->db->createCommand();
			$rows = $command
			    ->select('*')
			    ->from('{{categories}}')
			    ->where("shop_id=:shop_id $sql", array(':shop_id'=>$shop_id))
			    ->limit($limit,$offset)->queryAll();
			$command->reset();  // clean up the previous query
		    $total = $command->select('COUNT(*)')->from('{{categories}}')->where("shop_id=:shop_id $sql", array(':shop_id'=>$shop_id))->queryScalar();
			//$rows = $dataReader->readAll();
			foreach ($rows as &$row) {
				if($row['status'] > 0) {
					$row['status_text'] = '是';
				} else {
					$row['status_text'] = '否';
				}
			}
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));
		$this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FoodCategories('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FoodCategories']))
			$model->attributes=$_GET['FoodCategories'];

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
		$model=FoodCategories::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='food-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
