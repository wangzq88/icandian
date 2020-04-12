<?php

class PackageController extends Controller
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
			'postOnly + delete,create,update', // we only allow deletion via POST request
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
		//$model=new Package;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$html_str = '<script type="text/javascript">
		window.parent.document.getElementById("create_tip").innerHTML="woo ! 无法保存！";
		window.parent.document.getElementById("create_tip").style.display="block";		
		</script>';				
		$package_id = array();
		if($_POST['package_id'] && $_POST['package_price'] && is_array($_POST['package_id']) && is_array($_POST['package_price'])) {
			foreach($_POST['package_id'] as $key => $post) {
				$_POST['package_id'][$key] = explode(',',$post);
			}
			
			$all_list = array();
			$package_list = array();//套餐的组合  package_list
			$first_package = array_shift($_POST['package_id']);//取出第一个数组，$_POST['package_id']是个二维数组	
			$package_list[] = array_shift($first_package);//取出数组的第一个元素，$_POST['package_id'] 数目减1		
			$count = count($_POST['package_id']);
			if($count >= 1) {
				do {
					for($i = 0; $i < $count; $i++) {//循环第一维数组
						$tmp_list = array();
						while($item = array_shift($package_list)) {
							foreach($_POST['package_id'][$i] as $item0) {
								$tmp_list[] = $item.','.$item0;
							}
						}
						$package_list = $tmp_list;//if($i == 1) {print_r($tmp_list);exit();}
					}
					$all_list =  array_merge($all_list,$package_list);
					$package_list = array();
				} while($package_list[] = array_shift($first_package));

				$shop_id = Yii::app()->user->shop_id;
				$_POST['categories_id'] = (int)$_POST['categories_id'];
				$sub_sql = array();
				if(count($all_list) == count($_POST['package_price'])) {
					for($i = 0; $i < count($_POST['package_price']); $i++) {
						$package_price = (float)$_POST['package_price'][$i];		
						$sub_sql[$i] = "('{$all_list[$i]}','{$package_price}','{$_POST['categories_id']}','{$shop_id}')";
					}
					$sub_sql = implode(',',$sub_sql);
					$sql = "INSERT INTO `{{package}}` (food_ids,package_price,categories_id,shop_id) VALUES $sub_sql";
					$connection = Yii::app()->db;  
					$command = $connection->createCommand($sql);
					$rowCount = $command->execute(); 
					if($rowCount > 0) {
						Shop::model()->onFoodcount('1');
						$html_str = '<script type="text/javascript">
						window.parent.document.getElementById("create_tip").innerHTML="套餐已经创建成功！";
						window.parent.document.getElementById("create_tip").style.display="block";
						window.parent.jQuery("#dlg").dialog("close");
						window.parent.jQuery("#dg").datagrid("reload");				
						</script>';			
					}
				}
				
				
			}			
		}
		
		header('Content-Type: text/html; charset=utf-8');
		exit($html_str);			
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model = $this->loadModel((int)$_POST['package_id']);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
				$html_str = '<script type="text/javascript">
				window.parent.document.getElementById("edit_tip").innerHTML="woo ! 无法保存！";
				window.parent.document.getElementById("edit_tip").style.display="block";		
				</script>';	
			if($_POST['package_img_path'] && is_file(ROOT_PATH.$_POST['package_img_path']) 
			&& $_POST['package_img_path'] != $model->package_img ) {
				$extension = pathinfo ( $_POST['package_img_path'], PATHINFO_EXTENSION );
				$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' );
				if (in_array ( $extension, $fileTypes )) {
					$old_file = ROOT_PATH.$model->package_img;
					if(is_file ($old_file)) {
						unlink($old_file);
					}
					$upload_dir = $this->getUploadDir().DS;
					$upload_url = $this->getUploadUrl().'/';			

					$uploadfile = time().'.'.$extension;
					$upload_url .= $uploadfile;
					$uploadfile = $upload_dir.$uploadfile;
					if(rename(ROOT_PATH.$_POST['package_img_path'], $uploadfile)) {
						$_POST['package_img'] = $upload_url;
					} 												
				}
			}				
			$shop_id = Yii::app()->user->shop_id;
			$_POST['categories_id'] = (int)$_POST['categories_id'];
			$_POST['package_remark'] = trim(strip_tags($_POST['package_remark']));	
			$food_list = array();
			foreach($_POST['food_id'] as $food) {
				if(is_numeric($food)) {
					$food_list[] = $food;
				}
			}
			$_POST['food_ids'] = implode(',',$food_list);	
			$model->attributes=$_POST;
		if($model->save()) {
			$html_str = '<script type="text/javascript">
			window.parent.document.getElementById("edit_tip").innerHTML="套餐已经创建成功！";
			window.parent.document.getElementById("edit_tip").style.display="block";
			window.parent.jQuery("#edit-dlg").dialog("close");
			window.parent.jQuery("#dg").datagrid("reload");				
			</script>';			
		}
				header('Content-Type: text/html; charset=utf-8');
				exit($html_str);		

	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete()
	{
		$model = $this->loadModel((int)$_POST['package_id']);
		if($model->shop_id == Yii::app()->user->shop_id) {
			if($model->delete())
				Shop::model()->onFoodcount('2');
		}
		exit();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
/*		$dataProvider=new CActiveDataProvider('Package');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
		if($_POST) {
			$shop_id = Yii::app()->user->shop_id;
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$command = Yii::app()->db->createCommand();
			$rows = $command
			    ->select('*')
			    ->from('{{package}}')
			    ->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))
				->order('package_id desc')
			    ->limit($limit,$offset)->queryAll();
			$command->reset();  // clean up the previous query
		    $total = $command->select('COUNT(*)')->from('{{package}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryScalar();
			//$rows = $dataReader->readAll();
			$command->reset();  // clean up the previous query
			$f_rows = $command->select('*')->from('{{food}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryAll();			
			$command->reset();  // clean up the previous query
			$c_rows = $command->select('*')->from('{{categories}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryAll();
			foreach($rows as &$row) {
				//获取套餐 food_ids 对应的中文名称
				$food_name = array();
				$food_list = explode(',',$row['food_ids']);
				foreach($food_list as $key => $food) {
					//获取单个food_id
					switch($key) {
						case '0':
							$tmp = $key + 1;
							$row['food_id_'.$tmp] = $food;
						case '1':
							$tmp = $key + 1;
							$row['food_id_'.$tmp] = $food;							
					}
					foreach($f_rows as $f) {
						if($f['food_id'] == $food) {
							$food_name[] = $f['food_name'];
							break;
						}
					}					
				}				
				$food_name = implode('+',$food_name);
				$row['package_name'] = $food_name;
				
				foreach($c_rows as $c) {
					if($row['categories_id'] == $c['categories_id']) {
						$row['categories_name'] = $c['categories_name'];
					}
				}
			}
			$result = array ('total' => $total, 'rows' => $rows );
			header ( "Content-type: text/json; charset=utf-8" );
			exit(json_encode ( $result ));
		}
		$shop_id = Yii::app()->user->shop_id;
		$command = Yii::app()->db->createCommand();
		$c_rows = $command->select('*')->from('{{categories}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryAll();
		
		$this->render('index',array(
			'categories'=>$c_rows,
		));				
	}

	public function actionTree()
	{
		$shop_id = Yii::app()->user->shop_id;
		$command = Yii::app()->db->createCommand();
		$c_rows = $command->select('*')->from('{{categories}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryAll();
		
		$command->reset();  // clean up the previous query
		$rows = $command
			->select('*')
			->from('{{food}}')
			->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))
			->order('categories_id asc')
			->queryAll();
		$result = array();
		foreach($rows as &$row) {
			
			foreach($c_rows as $c) {
				if($row['categories_id'] == $c['categories_id']) {
					$row['categories_name'] = $c['categories_name'];
					if(!$result['c'.$c['categories_id']])
						$result['c'.$c['categories_id']] = array('id' => 'c'.$c['categories_id'],'text' => $c['categories_name']);
					$result['c'.$c['categories_id']]['children'][] = array('id' => $row['food_id'],'text' => $row['food_name'].'(￥'.$row['food_price'].')');					
				}
			}
		}
		$list = array();
		foreach($result as $item) {
			$list[] = $item;
		}
		header ( "Content-type: text/json; charset=utf-8" );
		exit(json_encode ( $list ));		
		
	}
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Package('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Package']))
			$model->attributes=$_GET['Package'];

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
		$model=Package::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function getUploadDir() {
		$upload_dir = SHOP_UPLOAD_PATH.DS.(int)Yii::app()->user->shop_id;
		if(!is_dir($upload_dir)) {
			mkdir($upload_dir,0777,true);
		}		
		return $upload_dir;
	}
	
	protected function getUploadUrl() {
		return SHOP_UPLOAD_URL.'/'.(int)Yii::app()->user->shop_id;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='package-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
}
