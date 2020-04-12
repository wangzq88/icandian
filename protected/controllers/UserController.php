<?php

class UserController extends Controller
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
				'actions'=>array('index','create','update','checking','sentEmail','activeEmail','updateEmail','updateMobile'),
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
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->uid));
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

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->uid));
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

	
	public function actionActiveEmail() {
		$info = '该链接已经过期';
		$style = 'alert-warning';
		if ($_SESSION['email_activation_code'] == $_GET['activation_code']) {
			$modle = User::model();
			$result = $modle->updateByPk(Yii::app()->user->id,array('email' => $_SESSION['active_email'],'valid_email' => 1));
			if ($result > 0) {
				Yii::app()->user->email = $_SESSION['active_email'];
				$info = '邮箱已经成功设置';	
				$style = 'alert-success';
			} else {
				$info = '发送未知错误，邮箱未能设置成功，请再试一次';	
				$style = 'alert-error';
			}
		}
		header('Location: /index.php?r=user&info='.urlencode($info).'&style='.$style);
		exit();			
	}
	
	public function actionSentEmail() {
		$_SESSION['email_confirm_code'] = md5(time());
		//如QQ登录，没有设置邮箱，直接跳转
		$result = array ('success' => 1, 'url' => '/index.php?r=user/updateEmail&confirm_code='.$_SESSION['email_confirm_code'] );	
		if (Yii::app()->user->email) {
			require_once Yii::app()->basePath . '/extensions/mailer/email.class.php';
			//已经设置邮箱，要发送一封邮件确认
			$mailsubject = "来自".Yii::app()->name."修改邮箱确认";//邮件主题
			$mailbody = "亲爱的".Yii::app()->user->username."：<br />
			您好！感谢您注册 ".Yii::app()->name." 。请您点击下面链接来修改你的邮箱：<br />
			<a href='http://".$_SERVER['SERVER_NAME']."/index.php?r=user/updateEmail&confirm_code=".$_SESSION['email_confirm_code']."'> 
			http://".$_SERVER['SERVER_NAME']."/index.php?r=user/updateEmail&confirm_code=".$_SESSION['email_confirm_code']."</a>
			<br />为了确保您的帐号安全，该链接仅当次访问有效。如果点击链接没反应，请您将上面的链接粘贴到浏览器地址栏中。请勿回复该邮件。";//邮件内容
			$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件				
			$smtp = new Smtp(SMTPSERVER,SMTPPORT,true,SMTPUSER,SMTPPASS);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
			$smtp->debug = false;//是否显示发送的调试信息
			$smtp->sendmail(Yii::app()->user->email, SMTPUSER, $mailsubject, $mailbody, $mailtype);
			$result = array ('success' => 2, 'info' => '发了一封邮件验证到您的邮箱，请登录您的邮箱来确认是您本人的操作' );			
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );			
	}
	
	public function actionUpdateEmail() {
		if ($_SESSION['email_confirm_code'] == $_REQUEST['confirm_code']) {
			if (isset($_POST) && $_POST) {
				$result = array ('success' => 0, 'info' => 'Email的格式不合法' );
				if(is_valid_email($_POST['email'])) {
					$record = User::model()->findByAttributes(array('email' => $_POST['email']));
					if (!$record) {
						require_once Yii::app()->basePath . '/extensions/mailer/email.class.php';
						$_SESSION['active_email'] = $_POST['email'];
						$_SESSION['email_activation_code'] = md5(time());
						$mailsubject = "来自".Yii::app()->name."的邮箱验证信";//邮件主题
						$mailbody = "亲爱的".Yii::app()->user->username."：<br />
						您好！感谢您注册 ".$_SERVER['SERVER_NAME']." 。请您点击下面链接来完成邮箱验证：<br />
						<a href='http://".$_SERVER['SERVER_NAME']."/index.php?r=user/activeEmail&activation_code=".$_SESSION['email_activation_code']."'> 
						http://".$_SERVER['SERVER_NAME']."/index.php?r=user/activeEmail&activation_code=".$_SESSION['email_activation_code']."</a>
						<br />为了确保您的帐号安全，该链接仅当次访问有效。如果点击链接没反应，请您将上面的链接粘贴到浏览器地址栏中。请勿回复该邮件。";//邮件内容
						$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件				
						$smtp = new Smtp(SMTPSERVER,SMTPPORT,true,SMTPUSER,SMTPPASS);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
						$smtp->debug = false;//是否显示发送的调试信息
						$smtp->sendmail($_POST['email'], SMTPUSER, $mailsubject, $mailbody, $mailtype);
						$result = array ('success' => 1, 'info' => '发了一封邮件验证到您的邮箱，请登录您的邮箱完成新的邮件地址设置' );
					} else {
						$result = array ('success' => 0, 'info' => '该邮箱已被使用' );
					}
				}			
				header ( "Content-type: text/json; charset=utf-8" );
				exit ( json_encode ( $result ) );						
			}
			$this->render('updateEmail');		
		} 
	}
	
	public function actionUpdateMobile()
	{
		if (isset($_POST) && $_POST) {
			$result = array ('success' => 0, 'info' => '手机号码的格式不合法' );		
			if (is_numeric($_POST['mobile'])) {
				$result = User::model()->updateByPk(Yii::app()->user->id,array('mobile' => $_POST['mobile']));
				if ($result > 0) {
					Yii::app()->user->mobile = $_POST['mobile'];
					$direct = isset($_POST['direct']) && $_POST['direct'] ? urldecode($_POST['direct']):'';
					$result = array ('success' => 1, 'info' => '手机号码已经成功设置','direct' =>  $direct);
				} else {
					$result = array ('success' => 0, 'info' => '发送未知错误，手机号码未能设置成功，请再试一次' );		
				}					
			}	
			header ( "Content-type: text/json; charset=utf-8" );
			exit ( json_encode ( $result ) );						
		}
		$this->render('updateMobile');
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$user = Yii::app()->user;
		$password_strength = $user->password_strength * 10;
		$security_text = $user->password_strength == 1 ? '低':'中'; 
		$security = $user->email ? $password_strength+30:$password_strength;
		$security_text = $user->email ? (isset($user->mobile) ? '高':'中'):'低';
		$security = isset($user->mobile) ? $security+30:$security;

		$this->render('index',array('user' => $user,'security' => $security,'security_text' => $security_text));
	}

	public function actionChecking()
	{
		$uid = Yii::app()->user->id;
//		$province_list = Province::model()->findAll();
//		$city_list = City::model()->findAll();
		$address_list = UserAddress::model()->recently()->findAll('uid=:uid',array(':uid'=>$uid));	
//		if (!$address_list) {
//			$this->redirect('/index.php?r=useraddress');
//		}
		$this->render('checking',array(
			'address_list'=>$address_list
		));	
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
