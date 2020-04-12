<?php if($this->total_page > 1):?>
	<?php 
		$con = $this->page;
		$i = 0;
	?>
<div class="pagination tc">
	<?php if(!$this->friendly):?>
  <ul>
    <li <?php if($this->page == 1):?>class="disabled"<?php endif;?>><a href="<?php echo preg_replace('/page=\d+/','page=1',$this->url);?>">«</a></li>
    <?php while($con+$i > 1 ) :?>
    	<?php if($i > 1): break; endif;?>
        	<?php if($con+$i > 2) :?>
    	<li><a href="<?php echo preg_replace('/page=\d+/','page='.($con+$i-2),$this->url);?>"><?php echo $con+$i-2;?></a></li>
       		<?php endif; ?>
        <?php $i++;?>
    <?php endwhile;?>
    <li class="active"><a href="<?php echo preg_replace('/page=\d+/','page='.$this->page,$this->url);?>"><?php echo $this->page;?></a></li>
    <?php 
		$con = $this->page;
		$i=0;
	?>
     <?php while($this->total_page > $con ) :?>
     <?php if($i > 1) break; ?>
    <li><a href="<?php echo preg_replace('/page=\d+/','page='.($con+1),$this->url);?>"><?php echo ++$con;?></a></li>
    <?php $i++;?>
    <?php endwhile;?>
    <li <?php if($this->page == $this->total_page):?>class="disabled"<?php endif;?>><a href="<?php echo preg_replace('/page=\d+/','page='.$this->total_page,$this->url);?>">»</a></li>
  </ul>
  <?php else:?>
    <?php
		$pos = strrpos($this->url,'.');
		$suffix = substr($this->url,$pos);
		$url = substr($this->url,0,$pos);
		$url .= '_%d'.$suffix;
	?>  
 <ul>
    <li <?php if($this->page == 1):?>class="disabled"<?php endif;?>><a href="<?php echo $this->url;?>">«</a></li>
    <?php while($con+$i > 1 ) :?>
    	<?php if($i > 1): break; endif;?>
        	<?php if($con+$i > 2) :?>
    	<li><a href="<?php echo sprintf($url,$con+$i-2);?>"><?php echo $con+$i-2;?></a></li>
       		<?php endif; ?>
        <?php $i++;?>
    <?php endwhile;?>
    <li class="active"><a href="<?php echo sprintf($url,$this->page);?>"><?php echo $this->page;?></a></li>
    <?php 
		$con = $this->page;
		$i=0;
	?>
     <?php while($this->total_page > $con ) :?>
     <?php if($i > 1) break; ?>
    <li><a href="<?php echo sprintf($url,$con+1);?>"><?php echo ++$con;?></a></li>
    <?php $i++;?>
    <?php endwhile;?>
    <li <?php if($this->page == $this->total_page):?>class="disabled"<?php endif;?>><a href="<?php echo sprintf($url,$this->total_page);?>">»</a></li>
  </ul>  
  <?php endif;?>
</div>
<?php endif;?>