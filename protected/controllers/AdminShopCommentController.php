<?php

class AdminShopCommentController extends Controller
{
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
	
	public function actionIndex()
	{
		if($_POST) {
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$_POST = daddslashes(dstrip_tags($_POST));
			$username = $_POST['username'];
			$status = isset($_POST['status']) ? intval($_POST['status']) : -1;
			$add_time = isset($_POST ['add_time']) ? $_POST ['add_time'] : '';		

			$subsql = ' 1=1 ';
			$subsql .= $username ? " AND username = '$username' ":"";
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
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{shop_comment}} WHERE 
			 $subsql  ORDER BY id DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($rows as &$row) {
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
		$sql = "UPDATE {{shop_comment}} SET status = $status WHERE id  IN (" . $_POST ['id'] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$result['success'] = $command->execute();
		$result['info'] = '已经更新';
		header ( "Content-type: text/json; charset=utf-8" );
		exit ( json_encode ( $result ) );		
	}
	
	public function actionDelete()
	{
		$ids = explode ( ',', $_POST ['id'] );
		foreach ( $ids as $id ) {
			if (! is_numeric ( $id )) {
				exit ();
			}
		}
		$sql = "DELETE FROM {{shop_comment}} WHERE id  IN (" . $_POST ['id'] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
	}	
}