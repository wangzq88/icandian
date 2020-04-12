<?php

class CollectionShopController extends Controller
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
				'actions'=>array('index','create','update','delete'),
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
		$model=new CollectionShop;
		$result = array('success' => false);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST))
		{
			$model->attributes=$_POST;
			$model->uid = Yii::app()->user->id;
			if($model->save()) {
				
				$sql = "UPDATE {{user}} SET collection_shop=collection_shop+1 WHERE uid=".Yii::app()->user->id;
				$command = Yii::app()->db->createCommand($sql);			
				$command->execute();	
				Yii::app()->user->collection_shop = Yii::app()->user->collection_shop + 1;			
				$result['success'] = $model->id;			
			}
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $result ));
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

		if(isset($_POST['CollectionShop']))
		{
			$model->attributes=$_POST['CollectionShop'];
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
	public function actionDelete()
	{
		$id = (int)$_POST['id'];
		$cs = $this->loadModel($id);
		if ($cs['uid'] == Yii::app()->user->id) {
			$success = $cs->delete();
			if ($success) {
				$sql = "UPDATE {{user}} SET collection_shop=collection_shop-1 WHERE uid=".Yii::app()->user->id;
				$command = Yii::app()->db->createCommand($sql);			
				$command->execute();
				Yii::app()->user->collection_shop = Yii::app()->user->collection_shop - 1;	
			}				
		}
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
		$sql = "SELECT SQL_CALC_FOUND_ROWS c.*,s.shop_name,s.shop_logo,s.shop_opening_hours,s.ordering_time  FROM {{collection_shop}} c 
		INNER JOIN {{shop}} s ON c.shop_id=s.shop_id WHERE c.uid=$uid ORDER BY c.id DESC LIMIT $start,$limit";
		$command = $connection->createCommand($sql);
		$rows = $command->queryAll();
		$command->reset();  // clean up the previous query
		$command->text = 'SELECT FOUND_ROWS()';
		$total = $command->queryScalar();			
		$total_page = ceil($total/$limit);
		if ($_REQUEST['page'] > $total_page) {
			$_REQUEST['page'] = $total_page;
		}
		$now = date('H:i');
		foreach ($rows as &$row) {
			$row['open'] = false;//是否真正营业状态当中,默认为否
			$shop_opening_hours = json_decode($row['shop_opening_hours'],true);
			//星期几的营业时间,星期中的第几天，数字表示.1（表示星期一）到 7（表示星期天）
			$shop_opening_hours = $shop_opening_hours[date('N')];
			//上午和下午的营业时间段
			$list = explode(' ',$shop_opening_hours);
			//上午的营业时间段
			$tmp = explode('-',$list[0]);
			//下午的营业时间
			$tmp1 = explode('-',$list[1]);			
			if ($now <= $tmp[1]) {
				$row['now_opening_hours'] = $tmp[0].'～'.$tmp[1];
				//处于这个时间段，表示正在营业当中
				if ($now > $tmp[0]) {
					$row['open'] = true;
				}
			} else {
				$row['now_opening_hours'] = $tmp1[0].'～'.$tmp1[1];
				if ($now >= $tmp1[0] && $now < $tmp1[1]) {
					$row['open'] = true;
				}
			}
			//说明中午没有休息，全天运营
			if ($tmp[1] == $tmp1[0]) {
				$row['now_opening_hours'] = $tmp[0].'～'.$tmp1[1];
			}
			//显示详细的营业时间
			$row['shop_opening_hours'] = $tmp[0].'～'.$tmp[1];
			if ($tmp1[0] != $tmp1[1]) {//如果前后时间相等，说明下午没有营业
				$row['shop_opening_hours'] .= ' '.$tmp1[0].'～'.$tmp1[1]; 
			}
			if ($tmp[1] == $tmp1[0]) {
				$row['shop_opening_hours'] = $tmp[0].'～'.$tmp1[1];
			}
			//显示详细的最佳订餐时间，包括上午和下午
			if($row['ordering_time']) {
				$ordering_time = json_decode($row['ordering_time'],true);
				if($ordering_time['1']) {
					$list = explode('-',$ordering_time['1']);
					$row['ordering_time'] = $list[0].'～'.$list[1];
				}
				//下午订餐时间
				if($ordering_time['2']) {
					$list1 = explode('-',$ordering_time['2']);
					if ($list[1] == $list1[0]) {//说明全天接受订餐，中间没空格的时间
						$row['ordering_time'] = $list[0].'～'.$list1[1];
					} elseif ($list1[0] != $list1[1]) {//不相等说明下午接受预订
						$row['ordering_time'] .= ' '.$list1[0].'～'.$list1[1];
					}
				}				
			}	
			//显示现在订餐时间
			if ($now <= $tmp[1]) {//如果现在处于上午的营业时间，显示上午的订餐时间
				$row['now_ordering_time'] = $list[0].'～'.$list[1];
			} else {
				$row['now_ordering_time'] = $list1[0].'～'.$list1[1];
			}					
			
		}
		
		$this->render('index',array(
			'collection_shop_list'=>$rows,
			'page' => $_REQUEST['page'],
			'total_page' => $total_page,
			'total' => (int)$total
		));		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new CollectionShop('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CollectionShop']))
			$model->attributes=$_GET['CollectionShop'];

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
		$model=CollectionShop::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='collection-shop-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
