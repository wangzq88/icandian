<?php

class AdminFeedbackController extends Controller
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
				'actions'=>array('index','delete','update'),
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
		$model=new Feedback;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Feedback']))
		{
			$model->attributes=$_POST['Feedback'];
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
	public function actionUpdate()
	{
		$result = array ('success' => 0, 'info' => '非法的参数' );
		$ids = explode ( ',', $_POST ['id'] );
		foreach ( $ids as $id ) {
			if (! is_numeric ( $id )) {
				exit (json_encode ( $result ));
			}
		}
		$status = ( int ) $_POST ['status'];
		$sql = "UPDATE {{feedback}} SET status = $status WHERE id  IN (" . $_POST ['id'] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$result['success'] = $command->execute();
		$result['info'] = '已经更新';
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );		
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$ids = explode ( ',', $_POST ['id'] );
		foreach ( $ids as $id ) {
			if (! is_numeric ( $id )) {
				exit ();
			}
		}
		$sql = "DELETE FROM {{feedback}} WHERE id  IN (" . $_POST ['id'] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$_POST = daddslashes(dstrip_tags($_POST));
			$username = $_POST['username'];
			$flag = (int)$_POST['flag'];
			$status = isset($_POST['status']) ? intval($_POST['status']) : -1;
			$add_time = isset($_POST ['add_time']) ? $_POST ['add_time'] : '';		

			$subsql = ' 1=1 ';
			$subsql .= $username ? " AND username = '$username' ":"";
			$subsql .= $flag ? " AND flag = '$flag'":"";
			$subsql .= $status != -1 ? " AND status = '$status'":"";
			if ($add_time && is_array($add_time)) {
				$add_time [0] = $add_time [0] ? strtotime($add_time [0]):'';
				$add_time [1] = $add_time [1] ? strtotime($add_time [1]):'';
				if ($add_time [0] && $add_time [1]) {
					$subsql .= " AND timestamp >= '{$add_time[0]}' AND timestamp <= '{$add_time[1]}' ";
				} elseif ($add_time [0] && ! $add_time [1]) {
					$subsql .= " AND timestamp >= '{$add_time[0]}'  ";
				} elseif (! $add_time [0] && $add_time [1]) {
					$subsql .= " AND timestamp <= '{$add_time[1]}'  ";
				}
			}
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{feedback}} WHERE 
			 $subsql  ORDER BY id DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($rows as &$row) {
		    	switch ($row['flag']) {
		    		case '1':
		    			$row['flag_text'] = '催单';
		    			break;
		    		case '2':
		    			$row['flag_text'] = '网站错误';
		    			break;
		    		case '3':
		    			$row['flag_text'] = '功能建议';
		    			break;
		    		default:
		    			$row['flag_text'] = '投诉';		    			
		    	}
		    	if ($row['status'] > 0) {
		    		$row['status_text'] = '是';	
		    	} else {
		    		$row['status_text'] = '否';	
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
		$model=new Feedback('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Feedback']))
			$model->attributes=$_GET['Feedback'];

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
		$model=Feedback::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='feedback-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
