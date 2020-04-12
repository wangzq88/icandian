<?php

class ShopCommentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/front';

	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'testLimit'=>0
			),
		);
	}	
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),		
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','captcha','replay'),
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
		$result = array('success' => false,'info'=>'不合法的验证码');
		$connection = Yii::app ()->db;
		$time = time () - 24 * 3600;
		$sql = "SELECT timestamp FROM {{order_item}} WHERE uid=" . Yii::app ()->user->id . " AND shop_id=" . ( int ) $_POST ['shop_id'] . " AND `timestamp` > $time ORDER BY item_id DESC LIMIT 1";
		$command = $connection->createCommand ( $sql );
		$timestamp = $command->queryScalar ();
		
		$command->reset ();
		$sql = "SELECT id FROM {{shop_comment}} WHERE parent_id=0 AND uid=" . Yii::app ()->user->id . " AND shop_id=" . ( int ) $_POST ['shop_id'] . " AND `timestamp` > $timestamp LIMIT 1";
		$command->text = $sql;
		$is_exist = $command->queryScalar ();
		if (! $is_exist) {
			$_POST = daddslashes ( dstrip_tags ( $_POST ) );
			$model = new ShopComment ();
			$model->uid = Yii::app ()->user->id;
			$model->username = Yii::app ()->user->username;
			$model->avatar = isset ( Yii::app ()->user->avatar ) ? Yii::app ()->user->avatar : '';
			$model->timestamp = time ();
			$model->content = $_POST ['content'];
			$model->parent_id = 0;
			$model->shop_id = ( int ) $_POST ['shop_id'];
			$model->status = 1;
			if ($model->save ()) {
				$result ['success'] = $model->id;
				$result ['info'] = '您的留言已经提交';
				$result ['content'] = $this->renderPartial ( '_view', array ('comment' => $model ), true );
			}
		} else {
			$result ['success'] = false;
			$result ['info'] = '您今天已经留言过，不能再次留言！';
		}
		header('content-type: application/json; charset=utf-8'); 
		exit(json_encode($result));	
	}

	public function actionReplay()
	{
		$result = array('success' => false,'info'=>'无法提交回复');
		if (!Yii::app()->user->isGuest && (Yii::app()->user->flag == 2 || Yii::app()->user->flag == 3)) {
			$_POST['content'] = dstrip_tags($_POST['content']);
			$_POST['parent_id'] = intval($_POST['parent_id']);
			$_POST['shop_id'] = intval($_POST['shop_id']);
			$command = Yii::app()->db->createCommand("SELECT id FROM {{shop_comment}} WHERE parent_id=".$_POST['parent_id']." LIMIT 1");
			$exist = $command->queryScalar();
			if (!$exist) {
				$model = new ShopComment;
				$model->uid = Yii::app()->user->id;
				$model->username = Yii::app()->user->username;
				$model->avatar = isset(Yii::app()->user->avatar) ? Yii::app()->user->avatar:'';
				$model->timestamp = time();
				$model->attributes = $_POST;
				if($model->save()) {
					$result['success'] = $model->id;
					$result['info'] = '你的回复已经成功提交';
					$result['content'] = $this->renderPartial('_replay',array('replay' => $model),true);
				}		
			} else {
				$result['success'] = false;
				$result['info'] = '每条留言只能有一次回复';
			}
		}
		header('content-type: application/json; charset=utf-8'); 
		exit(json_encode($result));			
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

		if(isset($_POST['ShopComment']))
		{
			$model->attributes=$_POST['ShopComment'];
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
		$shop = Shop::model()->getShopInfo(intval($_GET['id']),1);
		if ($shop) {	
			$shop = Shop::model()->handlerShopLogic($shop);
			$connection = Yii::app()->db;
			$uid = Yii::app()->user->id;
			$limit = 10;
			$_GET['id'] = intval($_GET['id']);
			$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
			$start = ($_REQUEST['page'] - 1) * $limit;
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{shop_comment}} WHERE shop_id=".$_GET['id']." AND parent_id=0 AND status=1 ORDER BY id DESC LIMIT $start,$limit";
			$command = $connection->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
			$total = $command->queryScalar();			
			$total_page = ceil($total/$limit);
			if ($_REQUEST['page'] > $total_page) {
				$_REQUEST['page'] = $total_page;
			}
			$id_list = array();
			foreach ($rows as $row) {
				$id_list[] = $row['id'];
			}
			if ($id_list) {
				$id_list = implode(',',$id_list);
				$command->reset(); 
				$command->text = "SELECT  * FROM {{shop_comment}} WHERE parent_id IN ($id_list) AND status=1 ";
				$replay_list = $command->queryAll();
				if ($replay_list) {
					foreach ($rows as &$row) {
						$row['replay_list'] = array();
						foreach ($replay_list as $replay) {
							if ($row['id'] == $replay['parent_id']) {
								$row['replay_list'][] = $replay;
							}
						}
					}
				}
			}
			$show = 0;
			if (!Yii::app()->user->isGuest) {
				$time = time() - 24*3600;
				$command->reset(); 
				$command->text = "SELECT item_id FROM {{order_item}} WHERE uid=$uid AND shop_id=".(int)$_GET['id']." AND `timestamp` > $time ORDER BY item_id DESC LIMIT 1";
				$show = $command->queryScalar();
			}
			$this->render('index',array(
				'shop' => $shop,
				'comment_list'=>$rows,
				'page' => $_REQUEST['page'],
				'total_page' => $total_page,
				'show' => $show
			));					
		} else {
			throw new CHttpException(404,'该链接不存在.');			
		}
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ShopComment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ShopComment']))
			$model->attributes=$_GET['ShopComment'];

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
		$model=ShopComment::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='shop-comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
