//QQ登录或者微博登陆
var childWindow;
function toQzoneLogin()
{
	childWindow = window.open("/index.php?r=site/redirectQQLogin","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
} 
function toWeiboLogin()
{
	childWindow = window.open("/index.php?r=site/redirectWeiboLogin","WeiboLogin","width=710,height=520,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
}             
function closeChildWindow()
{
	childWindow.close();
}

function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name)
{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
	{
 		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  		x=x.replace(/^\s+|\s+$/g,"");
  		if (x==c_name)
    	{
    		return unescape(y);
    	}
  	}
}
function enterKeyEventHandler(event,callback) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (!$.isFunction(callback)) {
        try{
            console.error('第二个参数请传递一个函数');
        }catch(e){}
        return false;
    }		
	if (keyCode == 13) {
		if(event.type == 'keyup') {
			callback();	
		}
		return false
	}	
	return true;	
}	
function refreshCartCount(transaction,results)
{
	var food_count = 0;
	if(systemDB) {
		if(results.rows.length > 0) 
		{ 
			for(var i=0;i < results.rows.length;i++) 
			{
				food_count += parseInt(results.rows.item(i).amount);
			}
		}
	} else {
		if(results.length > 0) 
		{
			for(var i=0;i < results.length;i++) 
			{
				food_count += parseInt(results[i].amount);
			}
		}
	}
	$("#shop-cart-food-count").text(food_count);
}

function refreshCartCountAction(uid)
{
	if(systemDB) {		
		systemDB.transaction(
			 function (transaction) {			 
				 transaction.executeSql('SELECT * FROM shopping WHERE uid=? AND status=? ORDER BY id;',[uid,0],refreshCartCount,errorHandler);
			 }
		);		
	} else if(typeof(Storage)!=="undefined") {
		if(localStorage.shopping) {
			var item_list = $.parseJSON(localStorage.shopping);
			var i=0,result_list = new Array();
			for(i in item_list) {
				if(item_list[i].uid == uid && item_list[i].status == 0)
					result_list.push(item_list[i]);
			}
			refreshCartCount('',result_list);						
		}
	}  else {
		var item_list = getCookie("shopping");
		if (item_list!=null && item_list!="")
		{
			var i=0,result_list = new Array();
			for(i in item_list) {
				if(item_list[i].uid == uid && item_list[i].status == 0)
					result_list.push(item_list[i]);
			}			
			refreshCartCount('',result_list);	
		}
	}
}
//刷新购物车
function refreshCart(item_list)
{
	var html = '',food_count=0,last_shop = i = 0;
	var hot_text = '';
	for(i in item_list) {
		if(last_shop != item_list[i].shop_id) {
			if(i != 0) {
				html += '</dl><dl class="car_lst"><dt class="car_lst_hd"><input type="checkbox" class="m0" name="shop_id" value="'+item_list[i].shop_id+'"/> <strong class="fb">'+item_list[i].shop_name+'</strong></dt>';
			} else {
				html += '<dl class="car_lst"><dt class="car_lst_hd"><input type="checkbox" class="m0" name="shop_id" value="'+item_list[i].shop_id+'"/> <strong class="fb">'+item_list[i].shop_name+'</strong></dt>';
			}
		}
		hot_text = item_list[i].is_hot > 0 ? '<span class="g9">（加辣）</span>':'';
		html += '<dd class="car_lst_bd clearfix"><div class="w100p fr"><a class="close" onclick="deleteItemAction('+item_list[i].id+')">×</a><h4 class="lh24"><strong class="fb">'+item_list[i].food_name+'</strong>'+hot_text+'</h4><p class="clearfix"><span class="fr"><em class="pr10 HeiTi f14">X '+item_list[i].amount+'</em><strong class="r6 Georgia">'+item_list[i].food_price+'元</strong></span><!--送靓汤一份--></p></div></dd>';
		last_shop = item_list[i].shop_id;
	}
	if(i != 0) html += '</dl>';
	$("#shopping-cart-item").children("dl").remove();
	$("#shopping-cart-item").prepend(html);
	refreshCartCountAction(identity_flag);
}

/*! Initialize the systemDB global variable. */
function initDB(database,isdelete)
{
	try {
		if (!window.openDatabase) {
			return false;
		} else {
			var shortName = database;
			var version = '1.0';
			var displayName = 'Shopping Information Database';
			var maxSize = 65536; // in bytes
			systemDB = openDatabase(shortName, version, displayName, maxSize);
			createTables(systemDB,isdelete);	 
			// You should have a database instance in myDB.
		}
	} catch(e) {
		// Error handling code goes here.
		if (e == INVALID_STATE_ERR) {
			// Version number mismatch.
			console.log("Invalid database version.");
		} else {
			console.log("Unknown error "+e+".");
		}
		return false;
	}
 	return true;
}

/*! Mark a file as "deleted". */
function updateItemAction(id,items,flag)
{
	if(systemDB) {
		var sql = '';
		if(flag == 1) {
			sql = 'UPDATE shopping SET amount=amount+1 WHERE id=? ';
		} else {
			sql = 'UPDATE shopping SET amount=amount-1 WHERE id=? ';
		}		
		systemDB.transaction(
			new Function("transaction", "transaction.executeSql('"+sql+"',[ "+id+" ],nullDataHandler, errorHandler);")
		);
	} else {
		var item_list = $.parseJSON(id);
		var i=0,food_item,tmp_list = new Array(),execute = false,myid;
		for(i in item_list) {
			myid = parseInt(i)+1;
			food_item = new Shopping(myid,item_list[i].food_id,item_list[i].food_name,item_list[i].amount,item_list[i].is_hot,item_list[i].is_package,item_list[i].shop_id,item_list[i].food_price,item_list[i].shop_name,item_list[i].uid,item_list[i].status);
			if((item_list[i].id == items.id && items.id != 0) || (food_item.food_id == items.food_id && food_item.uid == items.uid && food_item.status == items.status && food_item.is_package == items.is_package)) {
				if(flag == 1) {
					food_item.amount = food_item.amount + 1;
				} else {
					food_item.amount = food_item.amount - 1;
				}
				execute = true;
			}
			tmp_list.push(food_item);
		}
		if(!execute) {
			items.id = parseInt(i)+2;
			tmp_list.push(items);
		}
		item_list = tmp_list.toString();
		return item_list;
	}
}

function deleteShopFoodLogic(shop_id,identity_flag,item_list)
{
	var item_list = $.parseJSON(item_list);		
	var i=0,food_item,tmp_list = new Array(),myid;
	for(i in item_list) {
		if(item_list[i].shop_id != shop_id && item_list[i].uid == identity_flag) {
			food_item = new Shopping(item_list[i].id,item_list[i].food_id,item_list[i].food_name,item_list[i].amount,item_list[i].is_hot,item_list[i].is_package,item_list[i].shop_id,item_list[i].food_price,item_list[i].shop_name,item_list[i].uid,item_list[i].status);
			tmp_list.push(food_item);
		}
	}
	item_list = tmp_list.toString();
	return item_list;	
}

function deleteShopFood(shop_id,identity_flag)
{
	if(systemDB) {
		var sql = 'DELETE FROM shopping WHERE shop_id=? AND uid=?';	
		systemDB.transaction(
			new Function("transaction", "transaction.executeSql('"+sql+"',[ "+shop_id+",'"+identity_flag+"'],nullDataHandler, errorHandler);")
		);
	} else if(typeof(Storage)!=="undefined") {
		var item_list = deleteShopFoodLogic(shop_id,identity_flag,localStorage.shopping);
		localStorage.shopping = '['+item_list+']';
	} else {
		var shopping=getCookie("shopping");
		var item_list = deleteShopFoodLogic(shop_id,identity_flag,shopping);
		setCookie("shopping",'['+item_list+']');
	}
	refreshCartAction(identity_flag);
}

function deleteShopFoodAction(obj,identity_flag)
{
	popoverConfirm(obj,'提示','你确定要删除吗？','left',(function(obj,identity_flag) {
		return function() {
			var execute = false;
			$.each($("#shopping-cart-item input:checked"),function(i,n){
				execute = true;
				deleteShopFood($(n).val(),identity_flag);
			});		
			if(!execute) {
				popoverTooltip(obj,'请选择要删除的美食的餐店','left');
			}
		}
	})(obj,identity_flag));	
}

function deleteItem(id,item_list)
{
	var item_list = $.parseJSON(item_list);		
	var i=0,food_item,tmp_list = new Array(),myid;
	for(i in item_list) {
		if(item_list[i].id != id) {
			food_item = new Shopping(item_list[i].id,item_list[i].food_id,item_list[i].food_name,item_list[i].amount,item_list[i].is_hot,item_list[i].is_package,item_list[i].shop_id,item_list[i].food_price,item_list[i].shop_name,item_list[i].uid,item_list[i].status);
			tmp_list.push(food_item);
		}
	}
	item_list = tmp_list.toString();
	return item_list;
}
 
/*! Ask for user confirmation before deleting a file. */
function deleteItemAction(id)
{
	if(systemDB) {
		systemDB.transaction(
			new Function("transaction", "transaction.executeSql('DELETE from shopping where id=?;', [ "+id+" ], "+
				"nullDataHandler, errorHandler);")
		);
	} else if(typeof(Storage)!=="undefined") {
		var item_list = deleteItem(id,localStorage.shopping);
		localStorage.shopping = '['+item_list+']';
	} else {
		var shopping=getCookie("shopping");
		var item_list = deleteItem(id,shopping);
		setCookie("shopping",'['+item_list+']');
	}
	refreshCartAction(identity_flag);
}
 
function emptyAllItemAction()
{
    systemDB.transaction(
        function (transaction) {
           transaction.executeSql(' DELETE FROM shopping;', [],
            nullDataHandler, errorHandler);
        }
    );	
}
 
function refreshCartAction(uid)
{
	if(systemDB) {		
		var myfunc = new Function("transaction", "results",'var item_list = new Array();if(results.rows.length > 0){ for(i=0;i <  results.rows.length;i++) { item_list.push(results.rows.item(i)); } }refreshCart(item_list);');
		systemDB.transaction(
			 function (transaction) {
				 transaction.executeSql('SELECT * FROM shopping WHERE uid=? AND status=? ORDER BY shop_id;',[uid,0],myfunc,errorHandler);
			 }
		);		
	} else if(typeof(Storage)!=="undefined") {
		if(localStorage.shopping) {
			var results = $.parseJSON(localStorage.shopping);
			var i=0,item_list = new Array();
			for(i in results) {
				if(results[i].uid == uid && results[i].status == 0)
					item_list.push(results[i]);
			}
			refreshCart(item_list);
		}
	} else {
		var shopping=getCookie("shopping");
		if (shopping!=null && shopping!="")
		{
			var results = $.parseJSON(shopping);
			var i=0,item_list = new Array();
			for(i in results) {
				if(results[i].uid == uid && results[i].status == 0)
					item_list.push(results[i]);
			}
			refreshCart(item_list);
		}
		
	}
}


/*! This creates a new "file" in the database. */
function addItemAction(items)
{
	if(systemDB) {
		var myfunc = new Function("transaction", "results",
		"if(results.rows.length == 0) {"+
			"systemDB.transaction("+
				"function (transaction) {"+
					"transaction.executeSql('INSERT INTO shopping (food_id,food_name,amount,is_hot,is_package,shop_id,food_price,shop_name,uid,status) "+
					"VALUES (?,?,?,?,?,?,?,?,?,?);',"+
					" ["+items.food_id+",'"+items.food_name+"',1,"+items.is_hot+","+items.is_package+","+items.shop_id+","+items.food_price+",'"+items.shop_name+"','"+items.uid+"',0],nullDataHandler, errorHandler);"+
			"});"+
		"} else { "+
			"updateItemAction(results.rows.item(0)['id'],'',1);"+
		"}"+
		"refreshCartAction('"+items.uid+"');");
		//获取我购物车的某项美食
		systemDB.transaction(
			 function (transaction) {		 
				 transaction.executeSql('SELECT * FROM shopping WHERE food_id=? AND is_package=? AND uid=? AND status=?;',
				 [items.food_id,items.is_package,items.uid,0],myfunc,errorHandler);
			 }
		);
	} else if(typeof(Storage)!=="undefined") {
		var item_list = [items];
		item_list = item_list.toString();
		if(localStorage.shopping) {
			item_list = updateItemAction(localStorage.shopping,items,1);
		}
		localStorage.shopping = '['+item_list+']';
		refreshCartAction(items.uid);
	} else {
		var item_list = [items];
		item_list = item_list.toString();		
		var shopping=getCookie("shopping");
		if (shopping!=null && shopping!="")
		{
			item_list = updateItemAction(shopping,items,1);
		}
		setCookie("shopping",'['+item_list+']');
		refreshCartAction(items.uid);
	}
}
  
/*! This creates the database tables. */
function createTables(db,isdelete)
{
	/* To wipe out the table (if you are still experimenting with schemas,
	   for example), enable this block. */
	if (isdelete == 0) {
		db.transaction(
			function (transaction) {
				transaction.executeSql('DROP TABLE shopping;');
			}
		);
	}
	 
	db.transaction(
		function (transaction) {
			transaction.executeSql('CREATE TABLE IF NOT EXISTS shopping(id INTEGER PRIMARY KEY AUTOINCREMENT ,food_id INTEGER NOT NULL,food_name TEXT NOT NULL,amount INTEGER NOT NULL,is_hot INTEGER NOT  NULL,is_package INTEGER NOT  NULL,shop_id INTEGER NOT NULL,food_price REAL NOT NULL,shop_name TEXT NOT NULL,uid TEXT NOT NULL,status INTEGER NOT NULL);', [], nullDataHandler, killTransaction);
		}
	);
 
}
 
function dropTables(db) {
	db.transaction(
		function (transaction) {
			transaction.executeSql('DROP TABLE shopping;');
		}
	);	
}
 
/*! When passed as the error handler, this silently causes a transaction to fail. */
function killTransaction(transaction, error)
{
    return true; // fatal transaction error
}
 
/*! When passed as the error handler, this causes a transaction to fail with a warning message. */
function errorHandler(transaction, error)
{
    // error.message is a human-readable string.
    // error.code is a numeric error code
    console.log('Oops.  Error was '+error.message+' (Code '+error.code+')');
 
    // Handle errors here
    var we_think_this_error_is_fatal = true;
    if (we_think_this_error_is_fatal) return true;
    return false;
}
 
/*! This is used as a data handler for a request that should return no data. */
function nullDataHandler(transaction, results)
{
}

function collectionFood(obj,shop_id,shop_name,food_id,food_name,food_price,is_package) {
	if(loginPopoverFilter('您必须登录后才能收藏美食喔')) {
		$.post('/index.php?r=collectionFood/create',{shop_id:shop_id,shop_name:shop_name,food_id:food_id,food_name:food_name,food_price:food_price,is_package:is_package},function(data) {
			popoverTooltip(obj,'已经收藏该美食');
		});
	}
}

function collectionShop(obj,shop_id) {
	if(loginPopoverFilter('您必须登录后才能收藏餐店喔')) {
		$.post('/index.php?r=collectionShop/create',{shop_id:shop_id},function(data) {
			popoverTooltip(obj,'已经收藏该餐店');
		});	
	}
}

function book_food(obj,food_id,is_package)
{
	$.post('/index.php?r=collectionFood/book',{food_id:food_id,is_package:is_package},function(data){
		if(data.success) {
			var food = data.food;
			var food_id = food.food_id;
			var is_hot = food.is_hot;
			var is_package = food.is_package;
			var food_name = food.food_name;
			var food_price = food.food_price;
			var shop_id = food.shop_id;
			var shop_name = food.shop_name;
			var uid = identity_flag;
			var food_item = new Shopping(0,food_id,food_name,1,is_hot,is_package,shop_id,food_price,shop_name,uid,0);
			addItemAction(food_item);		
			refreshCartCountAction(identity_flag);	
			refreshCartAction(identity_flag);			
		}
		popoverTooltip(obj,data.info);
	});		
}
		
function delete_collection_food(obj,id)
{
	popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
		return function() {
			$.post('/index.php?r=collectionFood/delete',{id:id},function(data){
				$(obj).closest('tr').remove();
			});	
		}
	})(obj,id));	
}

function delete_collection_shop(obj,id) {
	popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
		return function() {
			$.post('/index.php?r=collectionShop/delete',{id:id},function(data){
				$(obj).closest('li').remove();
			});
		}
	})(obj,id));		
}

function modalAlert(title,content) {
	$('#my-modal-alert').remove();
	var html = '<div id="my-modal-alert" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-alert-label" aria-hidden="true">'+
  '<div class="modal-header">'+
    '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
    '<h3 id="my-modal-alert-label">'+title+'</h3>'+
  '</div>'+
  '<div class="modal-body">'+
    '<p>'+content+'</p>'+
  '</div>'+
  '<div class="modal-footer">'+
    '<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>'+
  '</div>'+
'</div>';
	$('body').append(html);
	$('#my-modal-alert').modal('show');
}

function popoverTooltip(obj,info,placement) {
	placement = placement == undefined || $.trim(placement) == '' ? 'top':placement;
	$(obj).tooltip({
		title:info,
		placement:placement,
		trigger:''
	});
	$(obj).tooltip('show');
	setTimeout((function(obj) {return function() {$(obj).tooltip("destroy")}})(obj),3000);
}

function popoverConfirm(obj,title,info,placement,callback,closeCallback) {
	title = $.trim(title) == '' ? '提示':title;
	placement = placement == undefined || $.trim(placement) == '' ? 'top':placement;
	if($.trim(info) == '') return false;
	if (!$.isFunction(callback)) {
        try{
            console.error('第五个参数请传递一个函数');
        }catch(e){}
        return false;
    }	
	var myCallback = function() {
		callback();
		$(obj).popover('destroy');
	}
	var myCloseCallback = function() {
		if ($.isFunction(closeCallback)) {
            closeCallback();
        }			
		$(obj).popover('destroy');
	}

	$(obj).popover({
		html:true,
		placement:placement,
		title:title,
		content:'<div style="text-align:center;">'+info+'</div><br />'+
		'<div style="text-align:center;"><button class="btn btn-mini" type="button"'+
		' id="popover-confirm-ok-buttom">确定</button>'+
		'&nbsp;&nbsp;<button class="btn btn-mini" type="button" id="popover-confirm-cancel-buttom">取消</button></div>'
	});		
	$("body").off("click","#popover-confirm-ok-buttom", myCallback).on("click", "#popover-confirm-ok-buttom", myCallback);	
	$("body").off("click","#popover-confirm-cancel-buttom", myCloseCallback).on("click", "#popover-confirm-cancel-buttom",myCloseCallback);		
	$(obj).popover('show');
}

function deleteAddress(obj,id) {
	popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
		return function() {
			$.post('/index.php?r=useraddress/delete',{id:id},function(data) {
				$(obj).closest('tr').remove();
			});
		}
	})(obj,id));
}
		
function addAddressAction(obj) {
	var form = $(obj).closest('form');
	var address = $.trim(form.find("input[name='address']").val());
	if( address == '') return false;
	$(obj).button('loading');
	$.post(form.attr('action'),form.serialize(),function(data) {
		$(obj).button('reset');
		if(data.success) {
			$('#friendly-table-tip').remove();
			var html = '<tr>'+
				  '<td>'+data.address+'</td>'+
				  '<td>'+
					  '<div class="btn-group">'+
						'<a href="javascript:void(0);" class="btn" title="删除" onclick="deleteAddress(this,'+data.success+');"><i class="icon-trash"></i></a>'+
					  '</div>'+
				  '</td>'+
			'</tr>';
			$('#address_list tbody').prepend(html);
			form.find("input[name='address']").val('');
			if($.trim(data.direct) != '') {
				window.location = data.direct;
			}
		} else {
			 addFormTip(form,data.info,'alert-error');
		}
	});
	return false;
}

function addFormTip(selector,info,style) {
	$("#form-tip-info").remove();
	style = style !== undefined ? style :'';
	var info = '<div class="alert alert-block '+style+'" id="form-tip-info">'+
	'<a class="close" data-dismiss="alert">×</a>'+
                          '<h4 class="alert-heading">'+info+'</h4>'+
                        '</div>';	
	selector.prepend(info);
}


function updatePasswordAction(obj) {
	var old_password = $.trim($("#old").val());
	var new_password = $.trim($("#new").val());
	var repeat_password = $.trim($("#repeat").val());
	var form = $("#q_regedit_tab");
	if(old_password == '' || new_password=='' || repeat_password=='') 
	{
		return false;
	}
	if(new_password != repeat_password)
	{
		addFormTip(form,'新密码输入不一致，请重新输入！','alert-error');
		return false;
	}
	if(new_password.length < 6)
	{
		addFormTip(form,'密码的长度至少需要6位！','alert-error');
		return false;		
	}
	$(obj).button('loading');
    var rsa = new RSAKey();
	rsa.setPublic(public_key, public_length);	
	var old_password = rsa.encrypt(old_password);
	var new_password = rsa.encrypt(new_password);
	$.post(form.attr("action"),{old_password:old_password,new_password:new_password},
	function(data){
		$(obj).button('reset');
		addFormTip(form,data.info);
	});
}		
function calculateShopInfo(transaction,results)
{
	var shop_collect_list = new Array();
	if(systemDB) {
		if(results.rows.length > 0) 
		{ 
			for(var i=0;i <  results.rows.length;i++) 
			{ 
				shop_collect_list.push(results.rows.item(i));
			} 
		}
	} else {
		for(var i=0;i < results.length;i++) 
		{ 
			shop_collect_list.push(results[i]);
		} 		
	}
	$.data(document.body,'shop_collect_list', shop_collect_list);
}

function generateOrders(transaction,results)
{
	var html = '',first = true,j=0,food_count=0,all_price=0;
	var shop_collect_list = $.data(document.body,'shop_collect_list');	
	$('#my-shopping-cart tbody,#my-shopping-cart tfoot').remove();
	if(systemDB) {
		if(results.rows.length > 0) 
		{ 
			for(var i=0;i < results.rows.length;i++) 
			{
				food_count += parseInt(results.rows.item(i).amount);
				all_price += parseInt(results.rows.item(i).amount)*parseFloat(results.rows.item(i).food_price);
			}
			for(j=0;j < shop_collect_list.length; j++)
			{
				first = true;
				if(j != 0) {
					html += '</tbody>';
				}
				for(var i=0;i < results.rows.length;i++) 
				{
					
					if(shop_collect_list[j].shop_id == results.rows.item(i).shop_id) {
						if(first) {
							html += '<tbody><tr>'+
								'<td rowspan="'+shop_collect_list[j].count+'" class="shop">'+shop_collect_list[j].shop_name+'</td>';
							first = false;
						} else {
							html += '<tr>';
						}
						html += '<td>'+results.rows.item(i).food_name+'</td>'+
						'<td>'+
						'<div class="input-prepend input-append">'+
										'<span class="add-on"><a href="javascript:void(0);" class="icon-minus-sign" onclick="changeQuantityAction(this,2,'+results.rows.item(i).id+')"></a></span><input type="text" value="'+results.rows.item(i).amount+'" class="tc w20"/><span class="add-on"><a href="javascript:void(0);" class="icon-plus-sign" onclick="changeQuantityAction(this,1,'+results.rows.item(i).id+')" ></a></span>'+
									  '</div>'+
									'</td>'+
									'<td>￥<span class="r6">'+results.rows.item(i).food_price+'</span></td>'+
									'<td><a onclick="deleteOrdersAction(this,'+results.rows.item(i).id+')"  class="btn" title="删除"><i class="icon-trash"></i></a></td>'+
						'</tr>';
					}
				} 			
	
			}
			if(j != 0) {
				html += '</tr></tbody>'
				$('#my-shopping-cart thead').after(html);
			} 
		} 
	} else {
		if(results.length > 0) 
		{
			for(var i=0;i < results.length;i++) 
			{
				food_count += parseInt(results[i].amount);
				all_price += parseInt(results[i].amount)*parseFloat(results[i].food_price);
			}
						
			for(j=0;j < shop_collect_list.length; j++)
			{
				first = true;
				if(j != 0) {
					html += '</tbody>'
				}
				for(var i=0;i < results.length;i++) 
				{
					if(shop_collect_list[j].shop_id == results[i].shop_id) {
						if(first) {
							html += '<tbody><tr>'+
								'<td rowspan="'+shop_collect_list[j].count+'" class="shop">'+shop_collect_list[j].shop_name+'</td>';
							first = false;
						} else {
							html += '<tr>';
						}
						html += '<td>'+results[i].food_name+'</td>'+
						'<td>'+
						'<div class="input-prepend input-append">'+
										'<span class="add-on"><a href="javascript:void(0);" onclick="changeQuantityAction(this,2,'+results[i].id+')" class="icon-minus-sign"></a></span><input type="text" value="'+results[i].amount+'" class="tc w20"/><span class="add-on"><a href="javascript:void(0);" onclick="changeQuantityAction(this,1,'+results[i].id+')" class="icon-plus-sign"></a></span>'+
									  '</div>'+
									'</td>'+
									'<td>￥<span class="r6">'+results[i].food_price+'</span></td>'+
									'<td><a onclick="deleteOrdersAction(this,'+results[i].id+')" class="btn" title="删除"><i class="icon-trash"></i></a></td>'+
						'</tr>';
					}
				} 			
	
			}
			if(j != 0) {
				html += '</tr></tbody>';
				$('#my-shopping-cart thead').after(html);
			} 	
			 
		} 
	}
	var footer = '<tfoot>'+
							'<tr>'+
                                '<td class="tr" colspan="5"><span class="pr10">共份<strong class="r6"> '+food_count+' </strong>美食</span><span class="pr20">合计<b class="f20 fa r3"> '+all_price+' </b>元</span></td>'+
                            '</tr>'+                        
                        '</tfoot> ';		
	$('#my-shopping-cart').append(footer);
	refreshCartCountAction(identity_flag);				
}

function handlerShopInfo(item_list)
{
	var i = j = 0,result_list = new Array();
	for(i in item_list) {
		for(j = 0; j < result_list.length; j++) {
			if(item_list[i].shop_id == result_list[j].shop_id) {
				result_list[j].count = result_list[j].count+1;
				break;
			}					
		}
		if(result_list.length == 0 || result_list[j].shop_id != item_list[i].shop_id) {
			var obj = new Object();
			obj.shop_id = item_list[i].shop_id;
			obj.shop_name = item_list[i].shop_name;
			obj.count = 1;
			result_list.push(obj);
		}
	}
	return result_list;
}

function generateOrdersAction(uid)
{
	if(systemDB) {
		systemDB.transaction(
			new Function("transaction", "transaction.executeSql('SELECT COUNT(*) AS count,shop_id,shop_name FROM shopping WHERE uid=? AND status=? GROUP BY shop_id;', [ '"+uid+"',0], "+
				"calculateShopInfo, errorHandler);")
		);
		systemDB.transaction(
			 function (transaction) {			 
				 transaction.executeSql('SELECT * FROM shopping WHERE uid=? AND status=? ORDER BY id;',[uid,0],generateOrders,errorHandler);
			 }
		);				
	} else if(typeof(Storage)!=="undefined") {
		if(localStorage.shopping) {
			var item_list = $.parseJSON(localStorage.shopping);
			var result_list = handlerShopInfo(item_list);
			calculateShopInfo('',result_list);
			var i=0;result_list = new Array();
			for(i in item_list) {
				if(item_list[i].uid == uid && item_list[i].status == 0)
					result_list.push(item_list[i]);
			}
			generateOrders('',result_list);			
		}
	} else {
		var item_list = getCookie("shopping");
		if (item_list!=null && item_list!="")
		{
			var result_list = handlerShopInfo(item_list);
			calculateShopInfo('',result_list);
			var i=0;result_list = new Array();
			for(i in item_list) {
				if(item_list[i].uid == uid && item_list[i].status == 0)
					result_list.push(item_list[i]);
			}
			generateOrders('',result_list);						
		}
	}
}

function deleteOrdersAction(obj,id)
{
	if(systemDB) {	
		popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
			return function() {	
				systemDB.transaction(
					 function (transaction) {			 
						 transaction.executeSql('DELETE FROM shopping WHERE id=? ;',[id],nullDataHandler,errorHandler);				
					 }
				);
				window.location.reload();		
			}
		})(obj,id));						
	} else if(typeof(Storage)!=="undefined") {
		popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
			return function() {
				if(localStorage.shopping) {
					var item_list = deleteItem(id,localStorage.shopping);
					localStorage.shopping = '['+item_list+']';
					window.location.reload();	
				} 
			}
		})(obj,id));				
				
	} else {
		popoverConfirm(obj,'提示','你确定要删除吗？','top',(function(obj,id) {
			return function() {
				var shopping=getCookie("shopping");
				if (shopping!=null && shopping!="") {
					var item_list = deleteItem(id,shopping);
					setCookie("shopping",'['+item_list+']');	
					window.location.reload();			
				}
			}
		})(obj,id));			
	}
}

function changeQuantity(id,flag)
{
	if(systemDB) {
		updateItemAction(id,'',flag)			
	} else if(typeof(Storage)!=="undefined") {
		if(localStorage.shopping) {
			var items = {id:id};
			item_list = updateItemAction(localStorage.shopping,items,flag);
			localStorage.shopping = '['+item_list+']';
		}
	} else {
		var item_list=getCookie("shopping");
		if (item_list!=null && item_list!="")
		{
			var items = {id:id};
			item_list = updateItemAction(item_list,items,flag);
			setCookie("shopping",'['+item_list+']');
		}
	}
	refreshCartAction(identity_flag);
	generateOrdersAction(identity_flag);
}

function changeQuantityAction(obj,flag,id) {

	var value = $(obj).closest('div').children(':input').val();
	if(flag == 1) {
		if(value < 1000) {
			value = parseInt(value)+1;
			$(obj).closest('div').children(':input').val(value);
			changeQuantity(id,1);
		}
	} else {
		if(value > 1)  {
			value = parseInt(value)-1;
			$(obj).closest('div').children(':input').val(value);
			changeQuantity(id,2);
		}
	}
}

function submitOrder(id,uid) {
	var item_list = $.parseJSON(id);
	var i=0,food_item,tmp_list = new Array(),myid;
	for(i in item_list) {
		if(item_list[i].uid == uid && item_list[i].status == 0) {
			myid = parseInt(i)+1;
			food_item = new Shopping(myid,item_list[i].food_id,item_list[i].food_name,item_list[i].amount,item_list[i].is_hot,item_list[i].is_package,item_list[i].shop_id,item_list[i].food_price,item_list[i].shop_name,item_list[i].uid,item_list[i].status);				
			tmp_list.push(food_item);
		}
	}
	if(tmp_list.length > 0) {
		item_list = '['+tmp_list.toString()+']';
		var address_id = $('.table_address :radio').filter(':checked').val();
		var address = $('.table_address :radio').filter(':checked').next().text();
		$('#address_id').val(address_id);
		$('#address').val(address);
		$('#order').val(item_list);
		$('#submit-order-form').submit();
	}
}

function submitOrderAction(uid) {
	if(systemDB) {
		var myfunc = new Function("transaction", "results","var item_list = new Array();var myid,food_item;"+
		"if(results.rows.length > 0) {"+
			"for(i=0;i <  results.rows.length;i++) { "+
				"myid = parseInt(i)+1;"+
				"food_item = new Shopping(myid,results.rows.item(i).food_id,results.rows.item(i).food_name,results.rows.item(i).amount,results.rows.item(i).is_hot,results.rows.item(i).is_package,results.rows.item(i).shop_id,results.rows.item(i).food_price,results.rows.item(i).shop_name,results.rows.item(i).uid,results.rows.item(i).status);"+
				"item_list.push(food_item); "+
			"}"+ 
			"item_list = '['+item_list.toString()+']';"+
			"var address_id = $('.table_address :radio').filter(':checked').val();"+
			"var address = $('.table_address :radio').filter(':checked').next().text();"+
			"$('#address_id').val(address_id);"+
			"$('#address').val(address);"+
			"$('#order').val(item_list);"+
			"$('#submit-order-form').submit();"+
		"}");
		systemDB.transaction(
			 function (transaction) {		 
				 transaction.executeSql('SELECT * FROM shopping WHERE uid=? AND status=?;',
				 [uid,0],myfunc,errorHandler);
			 }
		);		
	} else if(typeof(Storage)!=="undefined" && localStorage.shopping) {
		//有订单才让用户提交
		submitOrder(localStorage.shopping,uid);
	} else {
		submitOrder(getCookie("shopping"),uid);
	}
}

function updateEmailAction(obj) {
	var form = $(obj).closest('form');
	var email = $.trim(form.find('input[name="email"]').val());
	if(!form.valid()) return false;	  
	$(obj).button('loading');	
	$.post(form.attr('action'),form.serialize(),function(data) {
		$(obj).button('reset');
		var style = data.success > 0 ? 'alert-success':'alert-error';
		addFormTip(form,data.info,style);
	});		
}

function updateMobileAction(obj) {
	var form = $(obj).closest('form');
	var mobile = $.trim(form.find('input[name="mobile"]').val());
	if(!form.valid()) return false;	  
	$(obj).button('loading');	
	$.post(form.attr('action'),form.serialize(),function(data) {
		$(obj).button('reset');
		var style = data.success > 0 ? 'alert-success':'alert-error';
		addFormTip(form,data.info,style);
		if(data.success > 0) {
			if($.trim(data.direct) != '') {
				window.location = data.direct;
			} else {			
				setTimeout('window.location="/index.php?r=user";',3000);
			}
		}
	});		
}

function triggerEmailRequestAction(obj) {
	var div = $(obj).closest('table').parent();
	addFormTip(div,'请稍候...','alert-info');
	$.get('/index.php?r=user/sentEmail',function(data) {
		if(data.success == 1) {
			location.href = data.url;
		} else {
			addFormTip(div,data.info,'alert-error');
		}
	});
}

function loginPopoverFilter(popover) {
	var flag = getCookie('is_login');
	if(flag == 0) {
		if(popover != undefined) {
			$('#mix-login-Modal').modal({show:true});
			$('#quick_login_box a:first').tab('show');
			addFormTip($("#q_login_tab"),popover,'alert-info');
		}
		return false;
	}
	return true;
}

function shopApply() {
	if(loginPopoverFilter('您必须先登录后才能申请开店')) {
		location.href = '/shopApply/create';
	}
}

function Shopping(id,food_id,food_name,amount,is_hot,is_package,shop_id,food_price,shop_name,uid,status) {
	this.id = id;
	this.food_id = food_id;
	this.food_name = food_name;
	this.amount = amount;
	this.is_hot = is_hot;
	this.is_package = is_package;
	this.shop_id = shop_id;
	this.food_price = food_price;
	this.shop_name = shop_name;
	this.uid = uid;
	this.status = status;
}	

Shopping.prototype.toString = function() {
	return '{"id":'+this.id+',"food_id":'+this.food_id+',"food_name":"'+this.food_name+'","amount":'+this.amount+',"is_hot":'+this.is_hot+',"is_package":'+this.is_package+',"shop_id":'+this.shop_id+',"food_price":'+this.food_price+',"shop_name":"'+this.shop_name+'","uid":"'+this.uid+'","status":'+this.status+'}';
}
var systemDB;
var access_count = getCookie('access_count');
if(access_count == 0) {
	if(typeof(Storage) !== "undefined") {
		localStorage.shopping = '';
	} else {
		setCookie("shopping",'');
	}
}
var public_key="00b0c2732193eebde5b2e278736a22977a5ee1bb99bea18c0681ad97484b4c7f681e963348eb80667b954534293b0a6cbe2f9651fc98c9ee833f343e719c97c670ead8bec704282f94d9873e083cfd41554f356f00aea38d2b07551733541b64790c2c8f400486fd662a3e95fd5edd2acf4d59ca97fad65cc59b8d10cbc5430c53";
var public_length="10001";
var result = initDB('icandian',access_count);
var identity_flag = getCookie('identity_flag');
jQuery(function($) {
	refreshCartCountAction(identity_flag);	
	refreshCartAction(identity_flag);
	$("#login-form").submit(function() {
		if($.trim($("#inputEmail").val()) == '' || $.trim($("#inputPassword").val()) == '') return false;
		var rsa = new RSAKey();
		rsa.setPublic(public_key, public_length);	
		var res = rsa.encrypt($("#inputPassword").val());
		if(res) {
			$("#LoginForm_encryption").val(res);
			$("#inputPassword").remove();
		}
		return true;
	});	
});