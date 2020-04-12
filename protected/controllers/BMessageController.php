<?php

class BMessageController extends Controller
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
                'application.filters.BussinessFilter'
            ),			
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$uid = Yii::app()->user->id;
		$model = Message::model()->findByPk($id,'receive_uid=:receive_uid AND flag=1',array(':receive_uid' => $uid));
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$_POST = daddslashes(dstrip_tags($_POST));
		$sql = "INSERT INTO {{message}} (`send_uid`, `send_name`, `receive_uid`,`receive_name`, `message`, `timestamp`, `flag`) 
		VALUES (".Yii::app()->user->id.",'".Yii::app()->user->username."',".ADMIN_ID.",'".ADMIN_NAME."','".$_POST['message']."',".time().",1)";
		$command = Yii::app()->db->createCommand($sql);	
		if($command->execute()) {
			$result = array('success' => 1,'info' => '你的信息已经成功发送！');
		} else {
			$result = array('success' => 0,'info' => '发生未知的错误，请再试一次！');
		}
		header('content-type: application/json; charset=utf-8'); 
		exit(json_encode($result));		
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$result = Message::model()->updateByPk(intval($_POST['id']), array('status' => 1),'receive_uid=:uid',array('uid' => Yii::app()->user->id));
		exit($result);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$result = array('success' => 0,'info' => '删除失败，请重新再试一次');
		$result['success'] = Message::model()->updateByPk(intval($_POST['id']),array('flag' => 0),'receive_uid=:uid',array('uid' => Yii::app()->user->id));
		if ($result['success'] > 0) {
			$result['info'] = '该信息已经删除'; 
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $result ));		
	}

	/**
	 * Lists all models.
	 */
	public function actionReceive()
	{
		if(isset($_POST) && $_POST) {
			$connection = Yii::app()->db;
			$uid = Yii::app()->user->id;
			$limit = 10; 	
			$_POST = daddslashes(dstrip_tags($_POST));
			$where = ' 1=1 ';
			$where .= $_POST['send_name'] ? " AND send_name='".$_POST['send_name']."' ":'';
			$where .= isset($_POST['status']) && $_POST['status'] >= 0 ? " AND status='".intval($_POST['status'])."' ":'';
			$where .= " AND receive_uid IN ($uid,0) AND flag=1  ";			
			$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
			$start = ($_REQUEST['page'] - 1) * $limit;
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{message}} WHERE $where ORDER BY id DESC LIMIT $start,$limit";
			$command = $connection->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
			$total = $command->queryScalar();			
			$total_page = ceil($total/$limit);
			if ($_REQUEST['page'] > $total_page) {
				$_REQUEST['page'] = $total_page;
			}
			foreach ($rows as &$row) {
				if ($row['status'] == 0) {
					$row['status_text'] = '未看过';
				} else {
					$row['status_text'] = '已看过';
				}
				$row['timestamp'] = date('Y-m-d H:i:s',$row['timestamp']);
			}
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}	
		$this->render('receive');
	}

	public function actionSend() {
		if(isset($_POST) && $_POST) {
			$connection = Yii::app()->db;
			$uid = Yii::app()->user->id;
			$limit = 10; 	
			$_POST = daddslashes(dstrip_tags($_POST));
			$where = ' 1=1 ';
			$where .= $_POST['receive_name'] ? " AND receive_name='".$_POST['receive_name']."' ":'';
			$where .= " AND send_uid = $uid ";			
			$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
			$start = ($_REQUEST['page'] - 1) * $limit;
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{message}} WHERE $where ORDER BY id DESC LIMIT $start,$limit";
			$command = $connection->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
			$total = $command->queryScalar();			
			$total_page = ceil($total/$limit);
			if ($_REQUEST['page'] > $total_page) {
				$_REQUEST['page'] = $total_page;
			}
			foreach ($rows as &$row) {
				$row['timestamp'] = date('Y-m-d H:i:s',$row['timestamp']);
			}
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}	
		$this->render('send');		
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Message::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='message-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
