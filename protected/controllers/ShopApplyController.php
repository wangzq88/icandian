<?php

class ShopApplyController extends Controller
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
				'actions'=>array('create','view','update'),
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
	public function actionView()
	{
		if(Yii::app()->user->flag == 2) {
			header('Location: /manage');
			exit();
		} elseif(Yii::app()->user->flag == 3) {
			header('Location: /admin');
			exit();			
		}
		$model = ShopApply::model()->findByAttributes(array('uid' => Yii::app()->user->id));
		if (!$model) {
			header('Location: /shopApply/create');
			exit();				
		}
		if ($model['status'] > '1') {
			header('Location: /shopApply/update');
			exit();				
		}
		$this->render('view');
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		if(Yii::app()->user->flag == 2) {
			header('Location: /manage');
			exit();
		} elseif(Yii::app()->user->flag == 3) {
			header('Location: /admin');
			exit();			
		}
		$model = ShopApply::model()->findByAttributes(array('uid' => Yii::app()->user->id));
		if(!$model) {
			if(isset($_POST) && $_POST)
			{
				$result = array('success' => 0,'info' => '你的申请未能成功提交，请联系管理员');
				$model=new ShopApply;			
				$model->attributes=$_POST;
				$model->status = '1';
				$model->uid = Yii::app()->user->id;
				if($model->save()) {
					$result['success'] = $model->id;
					$result['info'] = '你的申请已经成功提交，我们的客服会尽快的为你审核';
					$result['href'] = '/shopApply/view'; 
				}
				header('content-type: application/json; charset=utf-8'); 
				exit(json_encode($result));					
			}
			$this->render('create');			
		} else {
			if ($model['status'] == '1')
				header('Location: /shopApply/view');
			else 
				header('Location: /shopApply/update');
			exit();				
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		if(Yii::app()->user->flag == 2) {
			header('Location: /manage');
			exit();
		} elseif(Yii::app()->user->flag == 3) {
			header('Location: /admin');
			exit();			
		}
		$model = ShopApply::model()->findByAttributes(array('uid' => Yii::app()->user->id));		
		if (!$model) {
			header('Location: /shopApply/create');
			exit();				
		} else {
			if(isset($_POST) && $_POST)
			{
				$result = array('success' => 0,'info' => '你的申请未能成功提交，请联系管理员');		
				$model->attributes=$_POST;
				$model->status = '1';
				if($model->save()) {
					$result['success'] = $model->id;
					$result['info'] = '你的申请已经成功提交，我们的客服会尽快的为你审核';
					$result['href'] = '/shopApply/view'; 
				}
				header('content-type: application/json; charset=utf-8'); 
				exit(json_encode($result));					
			}			
			if ($model['status'] == '1') {
				header('Location: /shopApply/view');
				exit();
			}			
			$this->render('create',array(
				'model'=>$model,
			));
		}
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
		$dataProvider=new CActiveDataProvider('ShopApply');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
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
			echo CActiveForm::validate($model);
			Yii::app()->end();
	}
}
