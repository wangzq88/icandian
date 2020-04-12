<?php

class ShopController extends Controller
{
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
			array(
				'COutputCache +index',
				'duration'=>3600*12,
				'varyByParam'=>array('id'),
				'dependency'=>array(
					'class'=>'CChainedCacheDependency',
					'dependencies'=>array(
						new CDbCacheDependency('SELECT update_time FROM {{shop}} WHERE shop_id='.$_GET['id']),
					),
				),
			),
		);
	}	
	
	public function actionIndex()
	{
		$shop_id = (int)$_GET['id'];
		$row = Shop::model()->getShopInfo($shop_id,1);
		if ($row) {
			$row = Shop::model()->handlerShopLogic($row);
			$time = time();
			$week = date('N',$time);//星期中的第几天，数字表示.1（表示星期一）到 7（表示星期天）
			$month = date('n',$time);			
			//菜单
			$food_list = Food::model()->getFoodListByShopID($shop_id);
			$package_list = Package::model()->getPackageListByShopID($shop_id);
			$food_categories = array();
			if ($food_list && is_array($food_list)) {
				foreach ($food_list as $food) {
					if(!in_array($food['categories_id'],$food_categories)) {
						array_push($food_categories,$food['categories_id']);
					}
					
				}
				if ($package_list && is_array($package_list)) {
					foreach ($package_list as $package) {
						if(!in_array($package['categories_id'],$food_categories)) {
							array_push($food_categories,$package['categories_id']);
						}						
					}
				}
				$food_categories = FoodCategories::model()->getFoodCategoriesByIDs($food_categories,$shop_id);
				foreach ($food_list as &$food) {
					foreach ($food_categories as $cat) {
						if ($food['categories_id'] == $cat['categories_id']) {
							$food['categories_name'] = $cat['categories_name'];
							break;
						}
					}
					
					$food['show'] = false;//显示预订按钮,默认为否
					switch($food['flag']) {
						case '1':
							$food['flag_text'] = '按天供应';
							$food['show'] = true;
							break;
						case '2':
							$food['flag_text'] = '按周供应';
							break;
						case '3':
							$food['flag_text'] = '按月供应';
							break;
					}

					if($food['flag'] == 2) {
						$attribs_list = explode (',',$food['attribs']);
						$food['show'] = in_array($week, $attribs_list);
						$food['attribs_text'] = '周{0}';
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
						$food['attribs_text'] =  str_replace('{0}',$tmp,$food['attribs_text']);
					} elseif($food['flag'] == 3) {
						switch($food['attribs']) {
							case '1-15':
								$food['attribs_text'] = $month.'月1日—15日';
								break;	
							case '16-31':
								$food['attribs_text'] = $month.'月16日—31日';
								break;						
							case '1-10':
								$food['attribs_text'] = $month.'月1日—10日';
								break;	
							case '11-20':
								$food['attribs_text'] = $month.'月11日—20日';
								break;	
							case '21-31':
								$food['attribs_text'] = $month.'月21日—31日';
								break;	
							default:
								$food['attribs_text'] = '';
																																																					
						}
						if ($food['attribs']) {
							$tmp = explode('-',$food['attribs']);
							$date = date('j',$time);
							if ($date >= $tmp[0] && $date <= $tmp[1])
								$food['show'] = true;
						}
					} else {
						$food['attribs_text'] = '每日';
					}					
					$food['show'] = $food['is_book'] > 0 ? $food['show']:false;
					$food['show'] = $row['open'] === false ? false:$food['show'];	
					$food['show'] = $row['flag'] > 0 ? $food['show']:false;
				}
				if ($package_list && is_array($package_list)) {
					unset($food);
					foreach ($package_list as &$package) {
						foreach ($food_categories as $cat) {
							if ($package['categories_id'] == $cat['categories_id']) {
								$package['categories_name'] = $cat['categories_name'];
								break;
							}
						}						
						$food_ids = explode(',',$package['food_ids']);
						foreach ($food_ids as $food_id) {
							foreach ($food_list as $food) {
								if ($food_id == $food['food_id']) {
									$package['package_name'] = $package['package_name'] ? $package['package_name'].'+'.$food['food_name']:$food['food_name'];
									$package['show'] = isset($package['show']) && $package['show'] === false ? $package['show']:$food['show'];
									$package['flag'] = '4';
									$package['flag_text'] = $package['attribs_text'] = $package['show'] ? '有提供':'没提供';
									$package['is_hot'] = $package['is_hot'] ? $package['is_hot']:$food['is_hot'];  
									$package['is_facia'] = $package['is_facia'] ? $package['is_facia']:$food['is_facia'];  
									$package['is_package'] = 1;
									$package['food_id'] = $package['package_id'];
									$package['food_name'] = $package['package_name'];
									$package['food_price'] = $package['package_price'];
									$package['food_remark'] = $package['package_remark'];
									$package['food_img'] = $package['package_img'];
									break;
								}
							}
						}
					}	
				}					
				//每个美食分类有几个美食
				unset($food);
				foreach ($food_categories as &$cat) {
					$cat['food_count'] = 0;
					foreach ($food_list as $food) {
						if ($cat['categories_id'] == $food['categories_id']) {
							$cat['food_count']++;
						}
					}
				}				
			} else {
				$food_list = array();
			}	
			$food_list = array_merge($food_list,$package_list);
			$this->render('index',array('shop' => $row,'food_list' => $food_list,'food_categories' => $food_categories));
		}
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/

}