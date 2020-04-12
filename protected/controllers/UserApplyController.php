<?php

class UserApplyController extends Controller
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
				'actions'=>array('create','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','index','delete','update'),
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
	public function actionView($id,$activation_code)
	{
		$record = UserApply::model()->findByPk($id,'activation_code=:activation_code',array(':activation_code'=>$activation_code));
		if ($record) {
			$user = new User;
			$user->username = $record->username;
			$user->password = $record->password;
			$user->email = $record->email;
			$user->mobile = $record->mobile;
			$user->salt = $record->salt;
			$user->flag = $record->flag;
			$user->valid_email = 1;
			$user->password_strength = $record->password_strength;
			$user->status = 1;
			$user->timestamp = $record->timestamp;
			if($user->save()) {
				$record->delete();
				$this->render('view',array(
					'info'=>'感谢您注册'.Yii::app()->name.'，您的帐号已经成功激活，现在可以登录了',
				));		
				Yii::app()->end();				
			} 
		}
		$this->render('view',array(
			'info'=>'非法的链接，也可能是该链接已经过期，您必须重新注册帐号。',
		));			
		Yii::app()->end();
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new UserApply;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST))
		{
			require_once Yii::app()->basePath . '/extensions/rsa/myrsa.php';
			require_once Yii::app()->basePath . '/extensions/mailer/email.class.php';
			$result = array ('success' => 0, 'info' => '不能为空' );
			$time = time();
			$model->attributes=$_POST;
			if ($_POST['password']) {
				$_POST['password'] = decryptPassword($_POST['password']);
				$rand ='';
				for($i=0;$i<6;$i++) {
					$rand .= dechex(rand(1,15));
				}
				$model->salt = $rand;
				$model->password = md5($_POST['password'].$rand);				
			}
			$model->email = strip_tags($_POST['email']);
			$model->flag = '1';
			$model->password_strength = get_expression_composition($_POST['password']);
			$model->timestamp = $time;		
			$model->activation_code = md5($time);
			if($model->save()) {
				$result = array ('success' => $model->id, 'info' => '帐号已经注册成功！请登录你的邮箱激活你的帐号！' );
				$mailsubject = Yii::app()->name." —— 用户账号激活";//邮件主题
				$mailbody = "亲爱的".$model->username."：<br />
				您好！感谢您注册 ".$_SERVER['SERVER_NAME']." 。请您点击下面链接来激活你的帐户：<br />
				<a href='http://".$_SERVER['SERVER_NAME']."/index.php?r=userApply/view&id=".$model->id."&activation_code=".$model->activation_code."'> 
				http://".$_SERVER['SERVER_NAME']."/index.php?r=userApply/view&id=".$model->id."&activation_code=".$model->activation_code."</a>
				<br />为了确保您的帐号安全，该链接仅7天之内访问有效。如果点击链接没反应，请您将上面的链接粘贴到浏览器地址栏中。请勿回复该邮件。";//邮件内容
				$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件				
				$smtp = new Smtp(SMTPSERVER,SMTPPORT,true,SMTPUSER,SMTPPASS);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
				$smtp->debug = false;//是否显示发送的调试信息
				$smtp->sendmail($model->email, SMTPUSER, $mailsubject, $mailbody, $mailtype);				
			}
			header ( "Content-type: text/json; charset=utf-8" );
			exit ( json_encode ( $result ) );
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

		if(isset($_POST['UserApply']))
		{
			$model->attributes=$_POST['UserApply'];
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UserApply');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new UserApply('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserApply']))
			$model->attributes=$_GET['UserApply'];

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
		$model=UserApply::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-apply-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
