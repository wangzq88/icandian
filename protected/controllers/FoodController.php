<?php
require Yii::app()->basePath.DS.'config'.DS.'business.php';
class FoodController extends Controller
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
            ), // perform access control for CRUD operations
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
		$model=new Food;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$tip = 'woo ! 无法保存！';
		$html_str = '<script type="text/javascript">
		window.parent.document.getElementById("create_tip").innerHTML="{tip}";
		window.parent.jQuery("#create_tip").closest(".demo-info").show();	
		setTimeout(\'window.parent.jQuery("#create_tip").closest(".demo-info").hide();\',3000);
		</script>';		
		
		if(isset($_POST))
		{
			if ($_SESSION['food_count'] > MAX_FOOD_COUNT) {
				$tip = '对不起！一个餐店的美食数目不能超过 '.MAX_FOOD_COUNT.'道美食。 请体谅！';
			} else {
				$_POST = daddslashes(dstrip_tags($_POST));
				$model->shop_id = (int)Yii::app()->user->shop_id;
				$_POST['food_img'] = '';
				if($_POST['food_img_path'] && is_file(ROOT_PATH.$_POST['food_img_path'])) {
					$extension = pathinfo ( $_POST['food_img_path'], PATHINFO_EXTENSION );
					$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' );
					if (in_array ( $extension, $fileTypes )) {
						$upload_dir = $this->getUploadDir().DS;
						$upload_url = SHOP_UPLOAD_URL.'/'.(int)Yii::app()->user->shop_id.'/';
						$uploadfile = time().'.'.$extension;
						$upload_url .= $uploadfile;
						$uploadfile = $upload_dir.$uploadfile;
						if(rename(ROOT_PATH.$_POST['food_img_path'], $uploadfile)) {
							$_POST['food_img'] = $upload_url;
						} 					
					}
	
				}
				if($_POST['attribs'] && is_array($_POST['attribs'])) 
					$_POST['attribs'] = implode (',',$_POST['attribs']);			
				$_POST['food_remark'] = strip_tags($_POST['food_remark']);
				$model->attributes = $_POST;
	
				if($model->save()) {	
					Shop::model()->onFoodcount('1');
					$tip = '美食已经创建成功！';
					$html_str = '<script type="text/javascript">
					window.parent.document.getElementById("create_tip").innerHTML="{tip}";
					window.parent.jQuery("#create_tip").closest(".demo-info").show();
					setTimeout(\'window.parent.jQuery("#create_tip").closest(".demo-info").hide();\',3000);
					window.parent.jQuery("#dg").datagrid("reload");
					window.parent.resetNewFoodForm();				
					</script>';
				}
			}
			//				window.parent.jQuery("#dlg").dialog("close");
			//	$this->redirect(array('view','id'=>$model->food_id));
		}
				header('Content-Type: text/html; charset=utf-8');
				exit(str_replace('{tip}', $tip, $html_str));			
//		$this->render('create',array(
//			'model'=>$model,
//		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel((int)$_POST['food_id']);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
				$html_str = '<script type="text/javascript">
				window.parent.document.getElementById("edit_tip").innerHTML="woo ! 无法保存！";
				window.parent.jQuery("#edit_tip").closest(".demo-info").show();		
				setTimeout(\'window.parent.jQuery("#edit_tip").closest(".demo-info").hide();\',3000);
				</script>';		
		if(isset($_POST))
		{

			if($_POST['food_img_path'] && is_file(ROOT_PATH.$_POST['food_img_path']) && $_POST['food_img_path'] != $model->food_img ) {
				$extension = pathinfo ( $_POST['food_img_path'], PATHINFO_EXTENSION );
				$fileTypes = array ('jpg', 'jpeg', 'gif', 'png' );
				if (in_array ( $extension, $fileTypes )) {
					$old_file = ROOT_PATH.$model->food_img;
					if(is_file ($old_file)) {
						unlink($old_file);
					}
					$upload_dir = $this->getUploadDir().DS;
					$upload_url = $this->getUploadUrl().'/';			

					$uploadfile = time().'.'.$extension;
					$upload_url .= $uploadfile;
					$uploadfile = $upload_dir.$uploadfile;
					if(rename(ROOT_PATH.$_POST['food_img_path'], $uploadfile)) {
						$_POST['food_img'] = $upload_url;
					} 												
				}
			}
			if($_POST['attribs'] && is_array($_POST['attribs'])) 
				$_POST['attribs'] = implode (',',$_POST['attribs']);
			else
				$_POST['attribs'] = '';
			$_POST['food_remark'] = strip_tags($_POST['food_remark']);
			$_POST['shop_id'] = Yii::app()->user->shop_id;
			$model->attributes=$_POST;
			if($model->save()) {
				$html_str = '<script type="text/javascript">
				window.parent.document.getElementById("edit_tip").innerHTML="美食的信息已经更新！";
				window.parent.jQuery("#edit_tip").closest(".demo-info").show();
				setTimeout(\'window.parent.jQuery("#edit_tip").closest(".demo-info").hide();window.parent.jQuery("#edit-dlg").dialog("close");\',3000);
				window.parent.jQuery("#dg").datagrid("reload");				
				</script>';
			}
		//		$this->redirect(array('view','id'=>$model->food_id));
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
		$model = $this->loadModel((int)$_POST['food_id']);
		if($model->shop_id == (int)Yii::app()->user->shop_id) {
			if($model->delete()) {
				Shop::model()->onFoodcount('2');	
			}		
		}
		exit();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	//	if(!isset($_GET['ajax']))
		//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if($_POST) {
			$shop_id = Yii::app()->user->shop_id;
			$offset = ($_POST['page'] - 1) * $_POST['rows'];
			$limit = (int)$_POST['rows'];
			$food_name = strip_tags($_POST['food_name']);
			$categories_id = (int)$_POST['categories_id'];
			$is_hot = isset($_POST['is_hot']) ? intval($_POST['is_hot']):-1;
			$is_new = isset($_POST['is_new']) ? intval($_POST['is_new']):-1;
			$is_facia = isset($_POST['is_facia']) ? intval($_POST['is_facia']):-1;
			$is_book = isset($_POST['is_book']) ? intval($_POST['is_book']):-1;
			
			$sql = $categories_id ? "SET @categories_id = '$categories_id'":"SET @categories_id = NULL";
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();

			$command->reset();
			$command->text = $is_hot != -1 ? "SET @is_hot = '$is_hot'":"SET @is_hot = NULL";
			$command->execute();
			
			$command->reset();
			$command->text = $is_new != -1 ? "SET @is_new = '$is_new'":"SET @is_new = NULL";
			$command->execute();

			$command->reset();
			$command->text = $is_facia != -1 ? "SET @is_facia = '$is_facia'":"SET @is_facia = NULL";
			$command->execute();			

			$command->reset();
			$command->text = $is_facia != -1 ? "SET @is_book = '$is_book'":"SET @is_book = NULL";
			$command->execute();			
			
			$sql = $food_name ? " AND food_name LIKE '%{$food_name}%'":"";			
			$sql = " SELECT SQL_CALC_FOUND_ROWS * FROM {{food}} WHERE shop_id=$shop_id $sql AND 
				categories_id=(CASE WHEN @categories_id IS NULL THEN categories_id ELSE @categories_id END) AND
				is_hot=(CASE WHEN @is_hot IS NULL THEN is_hot ELSE @is_hot END) AND
				is_new=(CASE WHEN @is_new IS NULL THEN is_new ELSE @is_new END) AND 
				is_facia=(CASE WHEN @is_facia IS NULL THEN is_facia ELSE @is_facia END) AND
				is_book=(CASE WHEN @is_book IS NULL THEN is_book ELSE @is_book END)
				ORDER BY food_id DESC LIMIT $offset,$limit";
			$command->reset(); 	
			$command->text= $sql;
			$rows = $command->queryAll();
			$command->reset();  // clean up the previous query
		    $command->text = 'SELECT FOUND_ROWS()';
			$total = $command->queryScalar();	
			$command->reset();  // clean up the previous query
			$c_rows = $command->select('*')->from('{{categories}}')->where('shop_id=:shop_id', array(':shop_id'=>$shop_id))->queryAll();
			foreach($rows as &$row) {
			
				switch($row['is_book']) {
					case '0':
						$row['is_book_text'] = '否';
						break;
					default:
						$row['is_book_text'] = '是';
				}
								
				switch($row['is_new']) {
					case '0':
						$row['is_new_text'] = '否';
						break;
					case '1':
						$row['is_new_text'] = '是';
						break;
				}
				switch($row['is_hot']) {
					case '0':
						$row['is_hot_text'] = '否';
						break;
					case '1':
						$row['is_hot_text'] = '是';
						break;
				}				

				switch($row['is_facia']) {
					case '0':
						$row['is_facia_text'] = '否';
						break;
					case '1':
						$row['is_facia_text'] = '是';
						break;
				}
								
				switch($row['flag']) {
					case '1':
						$row['flag_text'] = '按天供应';
						break;
					case '2':
						$row['flag_text'] = '按周供应';
						break;
					case '3':
						$row['flag_text'] = '按月供应';
						break;
				}

				if($row['flag'] == 2) {
					$attribs_list = explode (',',$row['attribs']);
					$row['attribs_text'] = '每周星期{0}供应';
					$tmp = array();
					foreach($attribs_list as $attribs) {
						switch($attribs) {
							case '1':
								$tmp[] = '一';
								break;
							case '2':
								$tmp[] = '二';
								break;
							case '3':
								$tmp[] = '三';				
								break;
							case '4':
								$tmp[] = '四';
								break;	
							case '5':
								$tmp[] = '五';
								break;	
							case '6':
								$tmp[] = '六';
								break;	
							case '7':
								$tmp[] = '日';
								break;						
						}
					}
					$tmp = implode('、',$tmp);
					$row['attribs_text'] =  str_replace('{0}',$tmp,$row['attribs_text']);
				} elseif($row['flag'] == 3) {
					switch($row['attribs']) {
						case '1-15':
							$row['attribs_text'] = '每月1-15号供应';
							break;	
						case '16-31':
							$row['attribs_text'] = '每月16-31号供应';
							break;						
						case '1-10':
							$row['attribs_text'] = '每月1-10号供应';
							break;	
						case '11-20':
							$row['attribs_text'] = '每月11-20号供应';
							break;	
						case '21-31':
							$row['attribs_text'] = '每月21-31号供应';
							break;	
						default:
							$row['attribs_text'] = '';
																																																				
					}
				} else {
					$row['attribs_text'] = '';
				}
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
		$this->render('list',array(
			'categories'=>$c_rows,
		));		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Food('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Food']))
			$model->attributes=$_GET['Food'];

		$this->render('admin',array(
			'model'=>$model,
		));
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Food::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='food-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
