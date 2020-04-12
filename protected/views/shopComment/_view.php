 <dl class="mb10 clearfix">
                        <dt class="w10p fl"><img src="<?php echo $comment['avatar'] ? $comment['avatar']:'/images/avatar.jpg'?>" class="small_pic"/></dt>
                        <dd class="w90p fr">
                            <div class="pr bg_fe bd3 p10">
                                <span class="tip_arrow_yl pa"></span>
                                <span class="tip_arrow_yl2 pa"></span>
                                <p class="clearfix"><span class="fr">发表时间：<?php echo date('Y-m-d H:i',$comment['timestamp']);?></span><strong class="f14 fl o3"><?php echo $comment['username'];?></strong></p>
                                <p class="ptb5 lh24"><?php echo $comment['content'];?> </p>
                                <?php if(!Yii::app()->user->isGuest && Yii::app()->user->id == $shop['uid']):?><p class="tr"><a href="#"><span class="icon-comment"></span> 回复</a></p><?php endif;?>
                            </div>
                        </dd>
                    </dl>