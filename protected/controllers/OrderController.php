<?php

class OrderController extends Controller
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
				'actions'=>array('index','create','update','view'),
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
	public function actionView()
	{
		$_REQUEST['order_id'] = (int)$_REQUEST['order_id'];
		$order = Order::model()->findByAttributes(array('order_id' => $_REQUEST['order_id'],'uid'=>Yii::app()->user->id));
		$rows = array();
		$shop_list = array();
		if ($order) {
			$connection = Yii::app()->db;
			$limit = 10;
			$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
			$start = ($_REQUEST['page'] - 1) * $limit;		
			$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{order_item}} WHERE order_id={$_REQUEST['order_id']} 
			ORDER BY item_id DESC,shop_id ASC LIMIT $start,$limit";
			$command = $connection->createCommand($sql);
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
			$command->text = 'SELECT FOUND_ROWS()';
			$total = $command->queryScalar();			
			$total_page = ceil($total/$limit);
			if ($_REQUEST['page'] > $total_page) {
				$_REQUEST['page'] = $total_page;
			}
			//餐店
			if ($rows && is_array($rows)) {
				foreach ($rows as $row) {
					if(!array_key_exists($row['shop_id'],$shop_list)) {
						$shop = array('shop_id' => $row['shop_id'],'count' => 1);
						$shop_list[$row['shop_id']] = $shop;
					}  else {
						$shop_list[$row['shop_id']]['count'] = ++$shop_list[$row['shop_id']]['count'];
					}
				}
			}
		}
		$this->render('view',array(
			'order'=>$order,
			'item_list'=>$rows,
			'shop_list' => $shop_list,
			'page' => $_REQUEST['page'],
			'total_page' => $total_page
		));	
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Order;
		$time = time();
		$rand = (string)rand(0,9999);
		for($i = $rand.length;$i < 4; $i++) {
			$rand = '0'.$rand;
		}
		$order = $_POST['order'] ? json_decode($_POST['order'],true):array();
		if ($order && is_array($order)) {
			//校验
			if (!isset(Yii::app()->user->mobile) || empty(Yii::app()->user->mobile)) {
				$this->redirect('/index.php?r=user/checking&info='.urlencode('您必须先填写您的联系电话才能提交订单，商家会用这个号码联系您'));
			}
			if (empty($_POST['address'])) {
				$this->redirect('/index.php?r=user/checking&info='.urlencode('您必须先填写您的送餐地址才能提交订单'));
			}
			$model->order_number = date('YmdHis').$rand;
			$model->price = 0;
			foreach ($order as $orderItem) {
				$model->price += $orderItem['amount']*$orderItem['food_price'];
			}
			$model->phone = Yii::app()->user->mobile;
			$model->integration = floor($model->price);
			$model->uid = Yii::app()->user->id;
			//默认该订单已经处理
			$model->flag = 1;
			$model->timestamp = $time;
			$model->address = strip_tags($_POST['address']);
			$sql = 'INSERT INTO {{order_item}} (food_name,food_price,amount,shop_id,shop_name,order_id,uid,timestamp) VALUES ';
			if ($model->save()) {
				$success = $model->order_id;
				$last_shop = 0;
				$total = 0;
				$shop_list = array();
				$message = '';
				$message_list = array();
				foreach ($order as $item) {
					if(!in_array($item['shop_id'],$shop_list)) array_push($shop_list,intval($item['shop_id']));
					if ($item['shop_id'] != $last_shop)	{
						if ($last_shop != 0) {
							$message = mb_substr($message,0,mb_strlen($message)-1);
							$message .=  '，总共￥'.$total.'。';
							$message .=  '电话：'.Yii::app()->user->mobile.'。';
							$message .=  '地址：'.$model->address.'。';	
							$message .=  '订单：'.$model->order_number.'。';		
							array_push($message_list,array('message' => $message,'shop_id' => $last_shop));			
							$total = 0;	
						}
						$message = '尊敬的商家，有用户在您的餐店预订以下美食：';
					}
					$total += $item['amount'] * $item['food_price'];
					$message .=  $item['food_name'].$item['amount'].'份×￥'.$item['food_price'].'，';
					$sql .= "('{$item['food_name']}',{$item['food_price']},{$item['amount']},{$item['shop_id']},'{$item['shop_name']}',$success,".Yii::app()->user->id.",$time),";
					$last_shop = $item['shop_id'];
				}
				$message = mb_substr($message,0,mb_strlen($message)-1);
				$message .=  '，总共￥'.$total.'。';
				$message .=  '电话：'.Yii::app()->user->mobile.'。';
				$message .=  '地址：'.$model->address.'。';	
				$message .=  '订单：'.$model->order_number.'。';		
				array_push($message_list,array('message' => $message,'shop_id' => $last_shop));
				$sql = mb_substr($sql,0,mb_strlen($sql)-1);
				$connection = Yii::app()->db;
				$command = $connection->createCommand($sql);
				$rowCount = $command->execute();
				//获取商家的手机号码
				$shop_list = implode(',', $shop_list);
				$command->reset();
				$command->text = "SELECT shop_id,shop_mobile,uid FROM {{shop}} WHERE shop_id IN (".$shop_list.")";
				$shop_list = $command->queryAll();
				$sbl_sql = 'REPLACE INTO {{shop_book_log}} (`shop_id`,`timestamp`) VALUES ';
				$sql = 'INSERT INTO {{order_sms}} (`message`,`phone`,`shop_id`,`timestamp`,`send_uid`,`send_username`,`receive_uid`) VALUES ';
				foreach ($shop_list as $shop) {
					foreach ($message_list as $mes) {
						if ($shop['shop_id'] == $mes['shop_id']) {
							$sql .= " ('".$mes['message']."','".Yii::app()->user->mobile."',".$shop['shop_id'].",$time,".Yii::app()->user->id.",'".Yii::app()->user->username."',".$shop['uid']."),";
							$sbl_sql .= " (".$shop['shop_id'].",$time),";
							break;
						}
					} 
				}
				//记录发送的短信
				$command->reset();
				$command->text = mb_substr($sql,0,mb_strlen($sql)-1);
				$command->execute();
				//餐店有新的订单时，更新这个表
				$command->reset();
				$command->text = mb_substr($sbl_sql,0,mb_strlen($sbl_sql)-1);
				$command->execute();				
				//更改用户的积分
				$command->reset();
				$command->text = "UPDATE {{user}} SET integration = integration+".$model->integration." WHERE uid=".$model->uid;
				$command->execute();
				Yii::app()->user->integration +=  $model->integration;
				//写入积分日志表
				$history = new IntegrationHistory;
				$history->integration = $model->integration;
				$history->flag = '1';
				$history->primary_key = $success;
				$history->timestamp = $time;
				$history->save();
				if ($rowCount > 0) {
					$this->render('create',array(
						'model'=>$model,
					));				
				}
			}
		} else {
			$this->redirect('/index.php?r=user/checking');
		}
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

		if(isset($_POST['Order']))
		{
			$model->attributes=$_POST['Order'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->order_id));
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
		$connection = Yii::app()->db;
		$uid = Yii::app()->user->id;
		$limit = 10;
		$_REQUEST['page'] = $_REQUEST['page'] >= 1 ? intval($_REQUEST['page']):1;
		$start = ($_REQUEST['page'] - 1) * $limit;
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {{order}} WHERE uid=$uid AND flag=1 ORDER BY order_id DESC LIMIT $start,$limit";
		$command = $connection->createCommand($sql);
		$rows = $command->queryAll();
		$command->reset();  // clean up the previous query
		$command->text = 'SELECT FOUND_ROWS()';
		$total = $command->queryScalar();			
		$total_page = ceil($total/$limit);
		if ($_REQUEST['page'] > $total_page) {
			$_REQUEST['page'] = $total_page;
		}
		$this->render('index',array(
			'order_list'=>$rows,
			'page' => $_REQUEST['page'],
			'total_page' => $total_page
		));		
//		$dataProvider=new CActiveDataProvider('Order');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));

	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Order('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Order']))
			$model->attributes=$_GET['Order'];

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
		$model=Order::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='order-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
