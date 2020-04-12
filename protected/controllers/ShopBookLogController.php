<?php

class ShopBookLogController extends Controller
{
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
	
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('view'),
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
		$this->render('index');
	}

	public function actionView()
	{
		$sql = "SELECT `shop_id`,`timestamp` FROM {{shop_book_log}} WHERE shop_id=".Yii::app()->user->shop_id;
		$command = Yii::app()->db->createCommand($sql);
		$row = $command->queryRow();
		if (!$row)
			$row = array ('shop_id' => Yii::app()->user->shop_id, 'timestamp' => 0 ,'time' => date('m-d H:i:s'));
		$row['time'] = date('m-d H:i:s',$row['timestamp']);
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $row ));			
	}	

}