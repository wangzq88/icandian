<?php

class AdminController extends Controller
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
			'postOnly + delete,create', // we only allow deletion via POST request
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
				'actions'=>array('create','update','index','view','already','untreated'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','shopUpdate'),
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
		$model=new Admin;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FoodCategories']))
		{
			$model->shop_id = isset($_SESSION['shop_id']) ? $_SESSION['shop_id']:0;
			$model->categories_name =  strip_tags ($_POST['FoodCategories']['categories_name']);
			$model->categories_description =  strip_tags ($_POST['FoodCategories']['categories_description']);
		//	$model->attributes=$_POST['FoodCategories'];
			//$model->shop_id =
			$result = array('success' => 0,'info' => '不能为空'); 
			if($model->save()) {
		//	$model->save();		//$this->redirect(array('view','id'=>$model->categories_id));

				$result ['success'] = $model->categories_id ;
				$result ['info'] = '创建成功';
			}
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));						
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionShopUpdate()
	{
		$model = Shop::model()->findByPk( (int)$_POST['shop_id'] );
		
		$result = array ('success' => 0, 'info' => '不能为空' );
		if ($_POST) {
			$_POST = daddslashes(dstrip_tags($_POST));
			$model->shop_name = $_POST['shop_name'];
			$model->shop_address = $_POST['shop_address'];			
			$model->shop_province = $_POST['shop_province'];
			$model->shop_city = $_POST['shop_city'];
			$model->shop_region = $_POST['shop_region'];
			$model->shop_area = $_POST['shop_area'];
			$model->shop_section = $_POST['shop_section'];
			$model->coupon = (int)$_POST['coupon'];
			$model->status = (int)$_POST['status'];
			$model->ordering = (int)$_POST['ordering'];
			if ($model->save ()) {
				$result ['success'] = $model->shop_id;
				$result ['info'] = '创建成功';
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
		$this->render('index');
	}
	
	/**
	 * Lists all models.
	 */
	public function actionAlready()
	{
		$province_list = Province::model()->getAllProvinces();
		$city_list = City::model()->getAllCities();
		$region_list = Region::model()->findAll();		
		if($_POST) {
			$shop_name = strip_tags($_POST['shop_name']);
			$status = isset($_POST['status']) ? intval($_POST['status']):-1;
			$coupon = isset($_POST['coupon'])? intval($_POST['coupon']):-1;
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$subsql = ' 1=1 '; 
			$subsql .= $shop_name ? " shop_name = '$shop_name' ":"";
			$subsql .= $status != -1 ? " status = '$status' ":"";
			$subsql .= $coupon != -1 ? " coupon = '$coupon' " :"";			

			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{shop}} WHERE $subsql ORDER BY shop_id DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();

			$cuisine_list = Cuisine::model()->findAll();
			$uid_list = array();
			foreach ($rows as &$row) {
				
				$uid_list[] = $row['uid'];
				
				foreach ($province_list as $province) {
					if ($row['shop_province'] == $province['province_id']) {
						$row['province_name'] = $province['province_name'];
						break;
					}
				}
				
				foreach ($city_list as $city) {
					if ($row['shop_city'] == $city['city_id']) {
						$row['city_name'] = $city['city_name'];
						break;
					}
				}			

				foreach ($cuisine_list as $cuisine) {
					if ($row['shop_cuisine'] == $cuisine['cuisine_id']) {
						$row['cuisine_name'] = $cuisine['cuisine_name'];
						break;
					}					
				}
				
				if ($row['coupon'] == '0') {
					$row['coupon_text'] = '无';
				} else {
					$row['coupon_text'] = '有';
				}
				
				if ($row['flag'] == '0') {
					$row['flag_text'] = '关闭';
				} else {
					$row['flag_text'] = '开张';
				}
				
				if ($row['status'] == '0') {
					$row['status_text'] = '不通过';
				} else {
					$row['status_text'] = '通过';
				}				
			}			
			if ($uid_list) {
				$uid_list = implode(',',$uid_list);
				$command->reset();  // clean up the previous query
				$command->text = 'SELECT * FROM {{user}} WHERE uid IN ('.$uid_list.')';
				$user_list = $command->queryAll();
				unset($row);
				foreach ($rows as &$row) {
					foreach ($user_list as $user) {
						if ($row['uid'] == $user['uid']) {
							$row['user_name'] = $user['username'];
							break;
						}
					}
				}
			}
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}
		$this->render('already',array(
			'province_list'=>$province_list,
			'cities'=>$city_list,
			'regions'=>$region_list
		));
	}
	
	public function actionUntreated()
	{
		$province_list = Province::model()->getAllProvinces();
		$city_list = City::model()->getAllCities();
		$region_list = Region::model()->findAll();			
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$command = Yii::app()->db->createCommand();
			$rows = $command
			    ->select('*')
			    ->from('{{shop}}')
			    ->where("status=0")
			    ->limit($limit,$offset)->queryAll();
			$command->reset();  // clean up the previous query
		    $total = $command->select('COUNT(*)')->from('{{shop}}')->where("status=0")->queryScalar();
			//$rows = $dataReader->readAll();
			
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}
		$this->render('untreated',array(
			'province_list'=>$province_list,
			'cities'=>$city_list,
			'regions'=>$region_list
		));
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Admin('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admin']))
			$model->attributes=$_GET['Admin'];

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
		$model=Admin::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
