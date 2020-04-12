<?php

class AdminShopApplyController extends Controller
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
				'actions'=>array('index','check'),
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
		$model=new ShopApply;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ShopApply']))
		{
			$model->attributes=$_POST['ShopApply'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['ShopApply']))
		{
			$model->attributes=$_POST['ShopApply'];
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
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionCheck()
	{
		$result = array ('success' => 0, 'info' => '审核已经通过，不能再次设置审核状态。您可以到商家管理页面设置商家的状态！' );
		if (isset($_POST) && $_POST) {
			$_POST = daddslashes(dstrip_tags($_POST));
			$_POST['id'] = intval($_POST['id']);
			$_POST['status'] = intval($_POST['status']);
			$model = $this->loadModel($_POST['id']);	
			if ($model['status'] != '3') {
				$model['status'] = $_POST['status'];	 		
				$model['message'] = $_POST['message'];					
				$success = $model->save();
				if($_POST['status'] == 3) {
					if($success) {
						$result['success'] = $model['id'];
						$shop = new Shop;
						$shop['shop_name'] = $model['shop_name'];
						$shop['shop_description'] = $model['shop_description'];
						$shop['shop_address'] = $model['shop_address'];
						$shop['shop_mobile'] = $model['mobile'];
						$shop['status'] = 1;
						$shop['timestamp'] = time();
						$shop['uid'] = $model['uid'];
						if($shop->save()) {
							$user = User::model()->findByPk($model['uid']);
							$user['flag'] = '2';
							if($user->save()) {
								$result['info'] = '审核已经通过！';
							} else {
								$result['info'] = '审核状态已经更新，商家餐店也已经生成。但是用户身份也未能标识为商家！';
							}
						} else {
							$result['info'] = '审核状态已经更新，但是商家餐店未能生成，用户身份也未能标识为商家！';
						}
						$result['info'] = '审核已经通过！';
					} else {
						$result['info'] = '发生未知的错误，审核状态未能成功更新，请重新再试一次！';
					}
				} elseif ($_POST['status'] == '2') {
					$model->save();
					$result['success'] = $model['id'];
					$result['info'] = '审核已经设置为不通过！';
				} 
			} else {
				$result['info'] = '审核状态为非法值！';
			}
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $result ));		
	}
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if (isset($_POST) && $_POST) {
			$_POST = daddslashes(dstrip_tags($_POST));
			$status = isset($_POST['status']) ? intval($_POST['status']):-1;
	
			$where = ' 1=1 ';
			$where .= $_POST['shop_name'] ? " AND shop_name='".$_POST['shop_name']."' ":"";
			$where .= $_POST['xing_ming'] ? " AND xing_ming='".$_POST['xing_ming']."' ":"";
			$where .= $_POST['mobile'] ? " AND mobile='".$_POST['mobile']."' ":"";
			$where .= $status != -1 ? "  AND `status`=$status ":"";
			
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];		
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{shop_apply}} WHERE $where ORDER BY id DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();
		    foreach($rows as &$row) {
		    	switch ($row['status']) {
		    		case '1':
		    			$row['status_text'] = '未审核';
		    			break;
		    		case '2':
		    			$row['status_text'] = '审核不通过';
		    			break;
		    		case '3':
		    			$row['status_text'] = '审核通过';
		    			break;		    				    			
		    	}
		    }
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));		    
		}		

		$this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ShopApply('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ShopApply']))
			$model->attributes=$_GET['ShopApply'];

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
		$model=ShopApply::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='shop-apply-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
