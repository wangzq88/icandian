<?php

class AreaController extends Controller
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','index','view','getAreaList'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
		$result = array ('success' => 0, 'info' => '不能为空' );
		$model=new Area;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST))
		{
			$model->attributes=$_POST;
			if($model->save()) {
				OperationLog::model()->replace('area');	
				$result = array ('success' => $model->area_id, 'info' => '地段已经成功添加！' );
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
		$area_id = $_POST['area_id'];
		$model=$this->loadModel($area_id);
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST))
		{
			$model->attributes=$_POST;
			if($model->save()) {
				OperationLog::model()->replace('area');	
				$result = array ('success' => $model->area_id, 'info' => '地段已经成功更新！' );
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
/*		$dataProvider=new CActiveDataProvider('Area');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	    $provinces = Province::model()->getAllProvinces();	
		$cities = City::model()->getAllCities();	
		$regiones = Region::model()->getAllRegion();
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$shop_province = intval($_POST['shop_province']);
			$shop_city = intval($_POST['shop_city']);
			$shop_region = intval($_POST['shop_region']);
			$status = isset($_POST['status']) ? intval($_POST['status']):'-1';
			$area_name = strip_tags($_POST['area_name']);
			
			$sql = $area_name ? "SET @area_name = '$area_name'":"SET @area_name = NULL";
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();

			$command->reset();
			$command->text = $status > -1 ? "SET @status = '$status'":'SET @status = NULL'; 
			$command->execute();	
			
			$city_list = array();
			$region_list = array();
			$sub_sql = '';
			if ($shop_province > 0 ) {
				foreach ($cities as $city) {
					if ($city['province_id'] == $shop_province) {
						$city_list[] = $city['city_id'];
					}
				}
				foreach ($city_list as $city) {
					foreach ($regiones as $region) {
						if ($shop_city > 0 && $region['city_id'] == $shop_city) {
							$region_list[] = $region['region_id'];
						} elseif ($region['city_id'] == $city['city_id'] && empty($shop_city)) {
							$region_list[] = $region['region_id'];
						}
					}						
				}	
				$region_list = $shop_region > 0 ? array($shop_region):$region_list; 	
				if ($region_list) {
					$region_list = implode(',',$region_list);
					$sub_sql = ' AND region_id  IN ('.$region_list.')';
				} else {
					$sub_sql = ' AND region_id IN (0)';
				}									
			} 
			

			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{area}} WHERE 
			area_name=(CASE WHEN @area_name IS NULL THEN area_name ELSE @area_name END) AND 
			 `status`=(CASE WHEN @status IS NULL THEN `status` ELSE @status END) $sub_sql
			 ORDER BY area_id DESC,ordering DESC LIMIT $offset,$limit";
			$command->text = $sql;
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($cities as &$city) {
		    	foreach ($provinces as $province) {
		    		if ($city['province_id'] == $province['province_id']) {
		    			$city['province_name'] = $province['province_name'];
		    			break;
		    		}
		    	}
		    }
			unset($city);
			foreach ($regiones as &$region) {
		    	foreach ($cities as $city) {
		    		if ($city['city_id'] == $region['city_id']) {
		    			$region['city_name'] = $city['city_name'];
		    			$region['province_name'] = $city['province_name'];
		    			$region['province_id'] = $city['province_id'];
		    			break;
		    		}
		    	}
		    }		    
		    unset($region);
		    foreach ($rows as &$row) {
		    	foreach ($regiones as $region) {
		    		if ($row['region_id'] == $region['region_id']) {
		    			$row['region_name'] = $region['region_name'];
		    			$row['city_id'] = $region['city_id'];
		    			$row['city_name'] = $region['city_name'];
		    			$row['province_id'] = $region['province_id'];
		    			$row['province_name'] = $region['province_name'];
		    			break;
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
			'cities'=>$cities,
			'regiones'=>$regiones
		));
		
	}

	public function actionGetAreaList() {
		$_GET['region_id'] = intval($_GET['region_id']);
		$sql = "SELECT * FROM {{area}} WHERE region_id=".$_GET['region_id'];
		$command = Yii::app()->db->createCommand($sql);
		$rows = $command->queryAll();
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $rows ));	
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Area('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Area']))
			$model->attributes=$_GET['Area'];

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
		$model=Area::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='area-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
