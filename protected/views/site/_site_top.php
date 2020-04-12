<?php if($_SERVER["REQUEST_URI"] == '/'):?>
<script type="text/javascript">
	var indexurl = getCookie('indexurl');
	if(indexurl != null && indexurl != "")
		window.location = indexurl;
</script>
<?php endif;?>
<script type="text/javascript">
$(".store_lst_detail").hover(
  function () {
	$(this).addClass("pr").children("dd:last").show();
  }, 
  function () {
	$(this).removeClass("pr").children("dd:last").hide();
  }
);
$(".cuisine-list").click(function(){
	var cuisine = $(this).attr("cuisine");
	var position = location.href.lastIndexOf('/');
	var script = location.href.substr(position+1);
	var count = script.split('_').length;
	var href;
	switch(count) {
		case 1:
			href = '/index_cuisine_'+cuisine+'.html';
			break;
		case 2:
			href = script.replace(/^(\w+)_(\d+)\.html$/i, "$1_$2_cuisine_"+cuisine+".html");
			break;
		case 3:
			href = script.replace(/^(\w+)_(\d+)_(\d+)\.html$/i, "$1_$2_cuisine_"+cuisine+".html");
			href = script.replace(/^(\w+)_(\w+)_(\d+)\.html$/i, "$1_$2_"+cuisine+".html");
			break;	
		case 4:
			href = script.replace(/^(\w+)_(\d+)_(\w+)_(\d+)\.html$/i, "$1_$2_cuisine_"+cuisine+".html");
			break;	
		case 5:
			href = script.replace(/^(\w+)_(\d+)_(\w+)_(\d+)_(\d+)\.html$/i, "$1_$2_cuisine_"+cuisine+".html");
			break;									
	}
	setCookie("indexurl",href,30);
	window.location = href;
	
});
$(".pagination li a").click(function() {
	var href = $(this).attr("href")
	setCookie("indexurl",href,30);
	return true;
});
<?php if($_GET['cuisine']):?>
$(".cuisine-list").removeClass("active").filter('[cuisine="<?php echo $_GET['cuisine']?>"]').addClass("active")
<?php endif;?>
$(".region-list").click(function() {
	var outerWidth = 0;
	var dd_width = $(this).parent().outerWidth();
	var last_n,margin = 0;
	$.each($(this).prevAll(),function(i,n) {
		if(i != 0) {
			margin = $(n).offset().left - $(last_n).offset().left;
			margin = Math.abs(margin) - $(last_n).outerWidth();
			margin = Math.abs(margin);//margin 代表元素之间的间隔
			outerWidth += margin;
		} 
		last_n = n;
		outerWidth += $(n).outerWidth();
	});
	var pencent_left = Math.round(outerWidth/dd_width*100) + 2;
	$(this).addClass('active').siblings().removeClass('active');
	var regionid = $(this).attr('regionid');
	$(this).closest('dl').nextAll('div').hide().filter('[regionid="'+regionid+'"]').show().children('.tip_arrow_rt').css("left",pencent_left+'%');
	$(".area-list:visible").filter(".active").trigger("click");
});
$(".area-list").click(function() {
	$(".area-list:visible").removeClass("active");
	var areaid = $(this).attr("areaid");
	$(this).addClass("active");
	$(".hover_area_b").hide().filter('[areaid="'+areaid+'"]').show();
});
$(".section-list").click(function() {
	var sectionid = $(this).attr("sectionid");
	setCookie("indexurl",$(this).attr("href"),30);
	return true;
});
<?php if($_GET['area']):?>
var regionid = $('.hover_area_a a').filter('[areaid="<?php echo $_GET['area']?>"]').attr("regionid");
$(".region-list").removeClass("active").filter('[regionid="'+regionid+'"]').addClass("active").trigger("click");
$('.hover_area_a a').removeClass("active").filter('[areaid="<?php echo $_GET['area']?>"]').addClass("active").trigger("click");
$('.hover_area_b a').removeClass("active").filter('[areaid="<?php echo $_GET['area']?>"][sectionid="0"]').addClass("active");
<?php endif;?>
<?php if($_GET['section']):?>
var areaid = $('.hover_area_b a').filter('[sectionid="<?php echo $_GET['section']?>"]').attr("areaid");
var regionid = $('.hover_area_a a').filter('[areaid="'+areaid+'"]').attr("regionid");
$(".region-list").removeClass("active").filter('[regionid="'+regionid+'"]').addClass("active").trigger("click");
$('.hover_area_a a').removeClass("active").filter('[areaid="'+areaid+'"]').addClass("active").trigger("click");
$('.hover_area_b a').removeClass("active").filter('[sectionid="<?php echo $_GET['section']?>"]').addClass("active");
<?php endif;?>
</script>