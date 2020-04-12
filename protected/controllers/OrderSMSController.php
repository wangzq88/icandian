<?php

class OrderSMSController extends Controller
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
			$send_username = $_POST['send_username'];
			$phone = $_POST['phone'];
			$add_time = isset($_POST ['add_time']) ? $_POST ['add_time'] : '';		

			$subsql = ' 1=1 ';
			$subsql .= '  AND status>0 AND shop_id='.Yii::app()->user->shop_id.' ';
			$subsql .= $send_username ? " AND send_username = '$send_username' ":"";
			$subsql .= $phone ? " AND phone = '$phone'":"";
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
			
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{order_sms}} WHERE 
			 $subsql  ORDER BY id DESC LIMIT $offset,$limit";
			$command = Yii::app()->db->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
		    $total = $command->queryScalar();	
		    foreach ($rows as &$row) {
		    	switch ($row['flag']) {
		    		case '1':
		    			$row['flag_text'] = '未处理';
		    			break;
		    		case '2':
		    			$row['flag_text'] = '已处理';
		    			break;
		    		case '3':
		    			$row['flag_text'] = '作废';
		    			break;    			
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
		$flag = ( int ) $_POST ['flag'];
		$sql = "UPDATE {{order_sms}} SET flag = '$flag' WHERE id  IN (" . $_POST ['id'] . ")";
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
		$sql = "UPDATE {{order_sms}} SET status=0 WHERE id  IN (" . $_POST ['id'] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
	}	
	
}