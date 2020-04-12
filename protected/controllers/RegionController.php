<?php

class RegionController extends Controller
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
                'application.filters.ManageFilter'
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
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
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
		$result = array ('success' => 0, 'info' => '不能为空' );
		$model=new Region;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST))
		{
			$model->attributes=$_POST;
			if($model->save()) {		
				OperationLog::model()->replace('region');				
				$result = array ('success' => $model->region_id, 'info' => '区域已经成功添加！' );
			}
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );	
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$result = array ('success' => 0, 'info' => '不能为空' );
		$region_id = $_POST['region_id'];
		$model=$this->loadModel($region_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST))
		{
			$model->attributes=$_POST;
			if($model->save()) {
				OperationLog::model()->replace('region');	
				$result = array ('success' => $model->region_id, 'info' => '区域已经成功更新！' );
			}
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );	
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
/*		$dataProvider=new CActiveDataProvider('Region');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	    $provinces = Province::model()->getAllProvinces();	
		$cities = City::model()->getAllCities();		
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$shop_province = intval($_POST['shop_province']);
			$shop_city = intval($_POST['shop_city']);
			$status = isset($_POST['status']) ? intval($_POST['status']):'-1';
			$region_name = strip_tags($_POST['region_name']);
			
			$sql = $region_name ? "SET @region_name = '$region_name'":"SET @region_name = NULL";
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();

			$command->reset();
			$command->text = $status > -1 ? "SET @status = '$status'":'SET @status = NULL'; 
			$command->execute();	
			
			$city_list = array();
			$sub_sql = '';
			if ($shop_province > 0 && empty($shop_city)) {			
				foreach ($cities as $city) {
					if ($city['province_id'] == $shop_province && empty($shop_city)) {
						$city_list[] = $city['city_id'];
					}
				}
				if ($city_list) {
					$city_list = implode(',',$city_list);
					$sub_sql = ' AND city_id IN ('.$city_list.')';
				}				
			} elseif ($shop_city > 0) {	
				$sub_sql = "  AND `city_id`=$shop_city ";		
			}
			

			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{region}} WHERE 
			region_name=(CASE WHEN @region_name IS NULL THEN region_name ELSE @region_name END) AND 
			 `status`=(CASE WHEN @status IS NULL THEN `status` ELSE @status END) $sub_sql
			 ORDER BY region_id,ordering DESC LIMIT $offset,$limit";
			$command->text = $sql;
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($cities as &$city) {
		    	foreach ($provinces as $province) {
		    		if ($city['province_id'] == $province['province_id']) {
		    			$city['province_name'] = $province['province_name'];
		    		}
		    	}
		    }
			    
		    foreach ($rows as &$row) {
		    	foreach ($cities as $city) {
		    		if ($row['city_id'] == $city['city_id']) {
		    			$row['city_name'] = $city['city_name'];
		    			$row['province_id'] = $city['province_id'];
		    			$row['province_name'] = $city['province_name'];
		    		}
		    	}
		    	$row['status_text'] = $row['status'] > 0 ? '是':'否';
		    }
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));		    
		}
		$this->render('index',array(
			'province_list'=>$provinces,
			'cities'=>$cities
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Region('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Region']))
			$model->attributes=$_GET['Region'];

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
		$model=Region::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='region-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
