<?php

class CollectionFoodController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','create','delete','book'),
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
		$model = new CollectionFood;
		$result = array('success' => false);
		if(isset($_POST))
		{
			$cf = CollectionFood::model()->findByAttributes(array('food_id'=>(int)$_POST['food_id'],'is_package' => (int)$_POST['is_package'],'uid' => Yii::app()->user->id));
			if (!$cf) {
				$_POST['uid'] = Yii::app()->user->id;			
				$model->attributes = $_POST;
				$result['success'] = $model->save();
				if ($result['success']) {
					$sql = "UPDATE {{user}} SET collection_food=collection_food+1 WHERE uid=".Yii::app()->user->id;
					$command = Yii::app()->db->createCommand($sql);			
					$command->execute();
					Yii::app()->user->collection_food = Yii::app()->user->collection_food + 1;		
				}
			} else {
				$result['success'] = true;
			}
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $result ));
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

		if(isset($_POST['CollectionFood']))
		{
			$model->attributes=$_POST['CollectionFood'];
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
		$success = CollectionFood::model()->deleteByPk($id,'uid=:uid',array(':uid'=>Yii::app()->user->id));
		if ($success) {
			$sql = "UPDATE {{user}} SET collection_food=collection_food-1 WHERE uid=".Yii::app()->user->id;
			$command = Yii::app()->db->createCommand($sql);			
			$command->execute();			
			Yii::app()->user->collection_food = Yii::app()->user->collection_food - 1;			
		}		
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$connection = Yii::app()->db;
		$uid = Yii::app()->user->id;
		$limit = 10;
		$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
		$start = ($_REQUEST['page'] - 1) * $limit;
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{collection_food}} WHERE uid=$uid ORDER BY id DESC LIMIT $start,$limit";
		$command = $connection->createCommand($sql);
		$rows = $command->queryAll();
		$command->reset();  // clean up the previous query
		$command->text = 'SELECT FOUND_ROWS()';
		$total = $command->queryScalar();			
		$total_page = ceil($total/$limit);
		if ($_REQUEST['page'] > $total_page) {
			$_REQUEST['page'] = $total_page;
		}
		$this->render('index',array(
			'collection_food_list'=>$rows,
			'page' => $_REQUEST['page'],
			'total_page' => $total_page,
			'total' => $total
		));		
	}

	public function actionBook()
	{
		$isbook = false;  
		$mes = '该美食此时段不接受预订';
		$info = array();
		$food_id = (int)$_POST['food_id'];
		$is_package = (int)$_POST['is_package'];
		if ($is_package == '0') {
			$food = Food::model()->findByPk($food_id);
			$shop = Shop::model()->findByPk($food['shop_id'],'status=:status',array(':status' => 1));
			$time = time();
			$isbook =  Food::model()->getFoodStatus($food,$shop);//该美食是否可以预订
			$mes = $isbook ? '该美食已经加入你的购物车':$mes;
			$info = array('food_id' => $food['food_id'],'food_name' => $food['food_name'],
			'food_img' => $food['food_img'],'food_price' => $food['food_price'],'is_hot' => $food['is_hot'],'is_package' => 0,'shop_id' => $food['shop_id'],'shop_name' => $shop['shop_name']);
		} else {
			$package = Package::model()->findByPk($food_id);
			$shop = Shop::model()->findByPk($package['shop_id'],'status=:status',array(':status' => 1));
			if ($shop) {
				$sql = "SELECT * FROM {{food}} WHERE food_id IN (".$package['food_ids'].")";
				$command = Yii::app()->db->createCommand($sql);
				$food_list = $command->queryAll();
				foreach($food_list as $food) {
					$food['show'] = Food::model()->getFoodStatus($food,$shop);
					$package['package_name'] = $package['package_name'] ? $package['package_name'].'+'.$food['food_name']:$food['food_name'];
					$package['show'] = isset($package['show']) && $package['show'] === false ? $package['show']:$food['show'];
					$package['flag'] = '4';
					$package['flag_text'] = $package['attribs_text'] = $package['show'] ? '有提供':'没提供';
					$package['is_hot'] = $package['is_hot'] ? $package['is_hot']:$food['is_hot'];  
					$package['is_facia'] = $package['is_facia'] ? $package['is_facia']:$food['is_facia']; 
					$info = array('food_id' => $package['package_id'],'food_name' => $package['package_name'],
				'food_img' => $package['package_img'],'food_price' => $package['package_price'],'is_hot' => $package['is_hot'],'is_package' => 1,'shop_id' => $package['shop_id'],'shop_name' => $shop['shop_name']);
					
				}
				$isbook = $package['show']; 	
				$mes = $isbook ? '该美食已经加入你的购物车':$mes;				
			} else {
				$mes = '该餐店已关闭';
			}
		}	
		$result = array('success' => $isbook,'food' => $info,'info' => $mes);			
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $result ));		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CollectionFood('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CollectionFood']))
			$model->attributes=$_GET['CollectionFood'];

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
		$model=CollectionFood::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='collection-food-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
