<?php

class AdminUserController extends Controller
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
				'actions'=>array('create','index','update'),
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
		require Yii::app()->basePath . '/extensions/rsa/myrsa.php';
		$result = array ('success' => 0, 'info' => '各个字段不能为空' );		
		if(isset($_POST) && $_POST)
		{
			$_POST = daddslashes(dstrip_tags($_POST));
			if(!is_valid_email($_POST['email'])) {
				$result['info'] = '邮箱的格式不合法';
			} else {
				$model = new User;	
				if ($_POST['password']) {
					$_POST['password'] = decryptPassword($_POST['password']);
					$rand ='';
					for($i=0;$i<6;$i++) {
						$rand .= dechex(rand(1,15));
					}
					$model->salt = $rand;
					$model->password_strength = get_expression_composition($_POST['password']);		
					$model->password = md5($_POST['password'].$rand);		
				}			
				$model->username = $_POST['username'];
				$model->email = $_POST['email'];
				$model->valid_email = intval($_POST['valid_email']);
				$model->flag = (int)$_POST['flag'];
				$model->status = (int)$_POST['status'];		
				$model->timestamp = time();
	//			$model->attributes=$_POST['User'];
				if($model->save()) {
					$result['success'] = $model->uid;
					$result['info'] = '用户已经成功创建';
				} else {
					$result['info'] = '发生未知的错误，用户未能成功创建，请重新再试一次！';
				}

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
		require_once Yii::app()->basePath . '/extensions/rsa/myrsa.php';
		$result = array ('success' => 0, 'info' => '各个字段不能为空' );
		$uid = $_POST['uid'];
		$model=$this->loadModel($uid);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST))
		{
			$_POST = daddslashes(dstrip_tags($_POST));
			if ($_POST['password']) {
				$_POST['password'] = decryptPassword($_POST['password']);
				$rand ='';
				for($i=0;$i<6;$i++) {
					$rand .= dechex(rand(1,15));
				}
				$model->salt = $rand;
				$model->password_strength = get_expression_composition($_POST['password']);		
				$model->password = md5($_POST['password'].$rand);		
			}
			$model->username = $_POST['username'];
			$model->email = $_POST['email'];
			$model->valid_email = intval($_POST['valid_email']);
			$model->flag = (int)$_POST['flag'];
			$model->status = (int)$_POST['status'];
			if($model->save())
				$result = array ('success' => $model->uid, 'info' => '用户信息已经成功更新！' );
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
/*		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$username = strip_tags($_POST['username']);
			$email = strip_tags($_POST['email']);
			$mobile = isset($_POST['mobile']) ? strip_tags($_POST['mobile']):'0';
			$flag = (int)$_POST['flag'];
			$status = isset($_POST['status']) ? intval($_POST['status']) : -1;		
			$subsql = ' 1=1 ';
			
			$subsql .= $username ? " AND username = '$username' ":"";
			$subsql .= is_valid_email($email) ? " AND email = '$email' ":"";
			$subsql .= $mobile !=0 ? "  AND `mobile`='$mobile' ":"";
			$subsql .= $flag ? " AND flag = '$flag'":"";
			$subsql .= $status != -1 ? " AND status = '$status'":"";
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{user}} WHERE 
			 $subsql  ORDER BY uid DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($rows as &$row) {
		    	switch ($row['flag']) {
		    		case '1':
		    			$row['flag_text'] = '普通用户';
		    			break;
		    		case '2':
		    			$row['flag_text'] = '商家';
		    			break;
		    		case '3':
		    			$row['flag_text'] = '管理员';
		    			break;
		    		default:
		    			$row['flag_text'] = '未知身份';		    			
		    	}
		    	if ($row['status'] > 0) {
		    		$row['status_text'] = '是';	
		    	} else {
		    		$row['status_text'] = '否';	
		    	}
		    	if ($row['valid_email'] > 0) {
		    		$row['valid_email_text'] = '是';	
		    	} else {
		    		$row['valid_email_text'] = '否';	
		    	}		    	
		    	$row['timestamp'] = date('Y-m-d H:i:s',$row['timestamp']);
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
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

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
		$model=User::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
