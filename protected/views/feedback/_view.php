<dl class="mb10 clearfix">
  <dt class="w8p fl"><img src="/images/avatar.jpg" class="small_pic"/></dt>
  <dd class="w92p fr">
      <div class="pr bg_fe bd3 p10">
          <span class="tip_arrow_yl pa"></span>
          <span class="tip_arrow_yl2 pa"></span>
          <p><strong class="f14 o3"><?php echo $feedback['username'];?></strong></p>
          <p class="ptb5 lh24"><?php echo $feedback['content'];?> </p>
          <p><span class="g9 pr10">发表时间：<?php echo date('Y-m-d H:i',$feedback['timestamp']);?></span><a href="#"><span class="icon-comment"></span> 回复</a></p>
      </div>
  </dd>
</dl>