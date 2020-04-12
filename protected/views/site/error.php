<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="ms_wrap">
            <ul class="breadcrumb w960 mt10">
                <li>
                  <a href="/">首页</a> <span class="divider">/</span>
                </li>
                <li class="active">错误</li>
            </ul>
            <!--/面包屑-->        
            <div class="p10 bg_opacity clearfix">
              <div class="pc_main w100p">
                  <div class="hd bgl_c">
                      <strong class="fl ml10"><?php echo $code; ?></strong>
                  </div>
                  <div class="bd">
                      <ol class="sucessful_lst">
                      	<li>
                        	<dl class="w100p suc_detail">
                            	<dt class="fl w20p"><img src="/images/b_smile.png" /></dt>
                                <dd class="fr w80p">
                                	<p class="fb lh30"><?php echo CHtml::encode($message); ?></p>
                                </dd>
                            </dl>                         
                        </li>
                      </ol>
                  </div>                        
              </div>
              <!--/中心右边栏-->              

            </div>
			
        </div>