<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php 
ob_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php
$doc_url="product.html#udpate";
$support_email_question="编辑商品";log_admin($support_email_question);
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$colname_product = "-1";
if (isset($_GET['id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_product = sprintf("SELECT * FROM product WHERE id = %s", $colname_product);
$product = mysql_query($query_product, $localhost) ;
if(!$product){$logger->fatal("数据库操作失败:".$query_product);}
$row_product = mysql_fetch_assoc($product);
$totalRows_product = mysql_num_rows($product);
if($totalRows_product==0){
	// 如果没有相应的商品，那么跳转到商品列表页面
 	header ( "Location: " . "index.php" );
	return;
}
mysql_select_db($database_localhost, $localhost);
$query_brands = "SELECT id, name FROM brands";
$brands = mysql_query($query_brands, $localhost) ;
if(!$brands){$logger->fatal("数据库操作失败:".$query_brands);}
$row_brands = mysql_fetch_assoc($brands);
$totalRows_brands = mysql_num_rows($brands);

mysql_select_db($database_localhost, $localhost);
$query_product_types = "SELECT * FROM product_type WHERE pid = 0 and is_delete=0";
$product_types = mysql_query($query_product_types, $localhost) ;
if(!$product_types){$logger->fatal("数据库操作失败:".$query_product_types);}
$row_product_types = mysql_fetch_assoc($product_types);
$totalRows_product_types = mysql_num_rows($product_types);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

 if ((isset($_POST["form_op"])) && ($_POST["form_op"] == "update_order")) {
 	 
	 // 这里对字段进行验证
 	$validation->set_rules ( 'consumption_pointers', '消费积分', 'trim|required|integer' );
	$validation->set_rules ( 'user_level_pointers', '用户等级积分', 'trim|required|integer' );
	$validation->set_rules ( 'catalog_id', '分类', 'trim|required|is_natural' );
	$validation->set_rules ( 'is_promotion', '是否是促销', 'trim|required|is_natural|exact_length[1]' );
	$validation->set_rules ( 'promotion_price', '促销价格', 'trim|is_float' );
	$validation->set_rules ( 'promotion_start', '促销起始日期', 'trim|is_natural_no_zero' );
	$validation->set_rules ( 'promotion_end', '促销结束日期', 'trim|is_natural' );
 	$validation->set_rules ( 'is_shipping_free', '运费免费', 'trim|is_natural' );
	$validation->set_rules ( 'meta_keywords', 'meta关键词', 'trim' );
	$validation->set_rules ( 'meta_desc', 'meta描述', 'trim' );
	$validation->set_rules ( 'description', '描述', 'trim|required' );
	$validation->set_rules ( 'unit', '单位', 'trim|required' );
	$validation->set_rules ( 'weight', '重量', 'trim|required|is_natural' );
	$validation->set_rules ( 'is_virtual', '是否为虚拟', 'trim|required|is_natural' );
	$validation->set_rules ( 'name', '商品名称', 'trim|required' );
	$validation->set_rules ( 'ad_text', '广告text', 'trim|required' );
	$validation->set_rules ( 'price', '价格', 'trim|required|is_float' );
	$validation->set_rules ( 'market_price', '市场价格', 'trim|required|is_float' );
	$validation->set_rules ( 'is_on_sheft', '是否上架', 'trim|required|is_natural' );
	$validation->set_rules ( 'is_hot', '是否为热销商品', 'trim|required|is_natural' );
	$validation->set_rules ( 'is_season', '是否为当季商品', 'trim|required|is_natural' );
	$validation->set_rules ( 'is_recommanded', '是否为推荐商品', 'trim|required|is_natural' );
 	$validation->set_rules ( 'store_num', '库存', 'trim|required|is_natural' );
 	$validation->set_rules ( 'intro', '简介', 'trim|required' );
 	$validation->set_rules ( 'brand_id', '品牌', 'trim|required|is_natural' );
 	$validation->set_rules ( 'id', '商品id', 'trim|required|is_natural' );
 
 	if (! $validation->run ()) {
 		$error = $validation->error_string ( '', '</br>' );
		$logger->fatal("用户在添加商品的时候未通过验证：".$error);
 	}else{
	
	
	$on_sheft_time='';
	if($_POST['is_on_sheft']=='1' && $row_product['on_sheft_time']==null){
		 $on_sheft_time=date('Y-m-d H:i:s');
	}
	
	if($_POST['is_on_sheft']=='1' && $row_product['on_sheft_time']!=null){
		 $on_sheft_time=$row_product['on_sheft_time'];
	}

//	如果需要上架的话
 $updateSQL = sprintf("UPDATE product SET consumption_pointers=%s,user_level_pointers=%s,catalog_id=%s,is_promotion=%s,promotion_price=%s,promotion_start=%s,promotion_end=%s,is_shipping_free=%s, meta_keywords=%s, meta_desc=%s, description=%s, product_type_id=%s, unit=%s,weight=%s,is_virtual=%s,on_sheft_time=%s,name=%s, ad_text=%s, price=%s, market_price=%s, is_on_sheft=%s, is_hot=%s, is_season=%s, is_recommanded=%s, store_num=%s, intro=%s, brand_id=%s WHERE id=%s",
  						GetSQLValueString($_POST['consumption_pointers'], "int"),
   						GetSQLValueString($_POST['user_level_pointers'], "int"),
				 		GetSQLValueString($_POST['catalog_id'], "int"),
						GetSQLValueString($_POST['is_promotion'], "text"),
						GetSQLValueString($_POST['promotion_price'], "double"),
						GetSQLValueString($_POST['promotion_start'], "date"),
						GetSQLValueString($_POST['promotion_end'], "date"),
 						GetSQLValueString($_POST['is_shipping_free'], "int"),
						GetSQLValueString($_POST['meta_keywords'], "text"),
						GetSQLValueString($_POST['meta_desc'], "text"),
						GetSQLValueString($_POST['description'], "text"),
					   GetSQLValueString($_POST['product_type_id'], "text"),
 					   GetSQLValueString($_POST['unit'], "text"),
                       GetSQLValueString($_POST['weight'], "double"),
                       GetSQLValueString($_POST['is_virtual'], "int"),
					   GetSQLValueString($on_sheft_time, "date"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['ad_text'], "text"),
                       GetSQLValueString($_POST['price'], "double"),
                       GetSQLValueString($_POST['market_price'], "double"),
                       GetSQLValueString($_POST['is_on_sheft'], "int"),
                       GetSQLValueString($_POST['is_hot'], "text"),
                       GetSQLValueString($_POST['is_season'], "text"),
                       GetSQLValueString($_POST['is_recommanded'], "text"),
                       GetSQLValueString($_POST['store_num'], "int"),
                       GetSQLValueString($_POST['intro'], "text"),
					   GetSQLValueString($_POST['brand_id'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) ;
  // 如果数据库操作失败的话
  if(!$Result1){
	  $error="商品更新的数据库操作失败，请联系123phpshop寻求解决方案";
	  $logger->fatal("数据库操作失败:".$updateSQL);
  }else{
	  $updateGoTo = "../product/index.php";
	  header(sprintf("Location: %s", $updateGoTo));
	  return;
  }
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
<link href="/js/jquery-ui-1.11.4.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
</head>

<body>

  <span class="phpshop123_title">编辑商品信息</span>
  <div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="<?php echo isset($doc_url)?"http://www.123phpshop.com/doc/v1.5/".$doc_url:"http://www.123phpshop.com/doc/";?>">[文档]</a><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a href=mailto:service@123phpshop.com?subject=我在<?php echo $support_email_question;?>的时候遇到了问题，请支持 style="color:#CCCCCC;margin-left:3px;">[邮件支持]</a></div>
<a href="index.php"><input style="float:right;" type="submit" name="Submit2" value="商品列表" /></a>
	<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php"); ?>

<form method="post" name="form1"  id="form1"  enctype="multipart/form-data" action="<?php echo $editFormAction; ?>">
<input type="hidden" name="form_op" value="update_order">
<input type="hidden" name="id" value="<?php echo $row_product['id']; ?>">
  
   <div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all" style="border:none;background:none;">
	<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist" style="border:none;background:none;">
		<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-8" aria-selected="true" aria-expanded="true" style="background-color:#000000;"><a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-8">一般信息</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-9" aria-selected="false" aria-expanded="false"><a href="#tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-9">详细介绍</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-3" aria-labelledby="ui-id-10" aria-selected="false" aria-expanded="false"><a href="#tabs-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-10">其他信息</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-4" aria-labelledby="ui-id-11" aria-selected="false" aria-expanded="false"><a href="#tabs-4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-11">图片列表</a></li>
		<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-5" aria-labelledby="ui-id-12" aria-selected="false" aria-expanded="false"><a href="#tabs-5" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-11">设置属性</a></li>
	</ul>
	<div id="tabs-1" aria-labelledby="ui-id-8" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false" style="background-color:#FFFFFF;"><?php include($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/product/_common.php'); ?></div>
	<div id="tabs-2" aria-labelledby="ui-id-9" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;background-color:#FFFFFF;"><?php include($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/product/_intro.php'); ?></div>
	
	<div id="tabs-3" aria-labelledby="ui-id-10" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;background-color:#FFFFFF;"><?php include($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/product/_other_info.php'); ?></div>
	
	<div id="tabs-4" aria-labelledby="ui-id-11" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;background-color:#FFFFFF;"><?php include($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/product/_images.php'); ?></div>
	<div id="tabs-5" aria-labelledby="ui-id-12" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;background-color:#FFFFFF;"><?php include($_SERVER['DOCUMENT_ROOT'].'/admin/widgets/product/_attr.php'); ?></div>
	<input type="submit"   value="更新">
</div>
   
</form>
<script type="text/javascript" charset="utf-8" src="/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/js/ueditor/lang/zh-cn/zh-cn.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery-ui-1.11.4.custom/jquery-ui.js"></script>
<script>
$().ready(function(){
	$("#tabs" ).tabs();
	$( "#promotion_start" ).datepicker({ dateFormat: 'yy-mm-dd' }); // 初始化日历
	$( "#promotion_end" ).datepicker({ dateFormat: 'yy-mm-dd' }); // 初始化日历
 	$("#form1").validate({
        rules: {	
            name: {
                required: true,
				remote:{
                    url: "ajax_update_product_name.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'name': function(){return $("#name").val();},
						'id': function(){return <?php echo $colname_product;?>;}
                    }
				}
            },
            price: {
                required: true,
				number:true   
            } ,
            market_price: {
				 required: true,
				 number:true 					 
            },
			tags:{
				required: true
			},
            store_num: {
				 digits:true    				 
            } 
        },
        messages: {
			name: {
  				remote:"产品已存在"
            } 
        }
    });
});

//实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');


    function isFocus(e){
        alert(UE.getEditor('editor').isFocus());
        UE.dom.domUtils.preventDefault(e)
    }
    function setblur(e){
        UE.getEditor('editor').blur();
        UE.dom.domUtils.preventDefault(e)
    }
    function insertHtml() {
        var value = prompt('插入html代码', '');
        UE.getEditor('editor').execCommand('insertHtml', value)
    }
    function createEditor() {
        enableBtn();
        UE.getEditor('editor');
    }
    function getAllHtml() {
        alert(UE.getEditor('editor').getAllHtml())
    }
    function getContent() {
        var arr = [];
        arr.push("使用editor.getContent()方法可以获得编辑器的内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getContent());
        alert(arr.join("\n"));
    }
    function getPlainTxt() {
        var arr = [];
        arr.push("使用editor.getPlainTxt()方法可以获得编辑器的带格式的纯文本内容");
        arr.push("内容为：");
        arr.push(UE.getEditor('editor').getPlainTxt());
        alert(arr.join('\n'))
    }
    function setContent(isAppendTo) {
        var arr = [];
        arr.push("使用editor.setContent('欢迎使用ueditor')方法可以设置编辑器的内容");
        UE.getEditor('editor').setContent('欢迎使用ueditor', isAppendTo);
        alert(arr.join("\n"));
    }
    function setDisabled() {
        UE.getEditor('editor').setDisabled('fullscreen');
        disableBtn("enable");
    }

    function setEnabled() {
        UE.getEditor('editor').setEnabled();
        enableBtn();
    }

    function getText() {
        //当你点击按钮时编辑区域已经失去了焦点，如果直接用getText将不会得到内容，所以要在选回来，然后取得内容
        var range = UE.getEditor('editor').selection.getRange();
        range.select();
        var txt = UE.getEditor('editor').selection.getText();
        alert(txt)
    }

    function getContentTxt() {
        var arr = [];
        arr.push("使用editor.getContentTxt()方法可以获得编辑器的纯文本内容");
        arr.push("编辑器的纯文本内容为：");
        arr.push(UE.getEditor('editor').getContentTxt());
        alert(arr.join("\n"));
    }
    function hasContent() {
        var arr = [];
        arr.push("使用editor.hasContents()方法判断编辑器里是否有内容");
        arr.push("判断结果为：");
        arr.push(UE.getEditor('editor').hasContents());
        alert(arr.join("\n"));
    }
    function setFocus() {
        UE.getEditor('editor').focus();
    }
    function deleteEditor() {
        disableBtn();
        UE.getEditor('editor').destroy();
    }
    function disableBtn(str) {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            if (btn.id == str) {
                UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
            } else {
                btn.setAttribute("disabled", "true");
            }
        }
    }
    function enableBtn() {
        var div = document.getElementById('btns');
        var btns = UE.dom.domUtils.getElementsByTagName(div, "button");
        for (var i = 0, btn; btn = btns[i++];) {
            UE.dom.domUtils.removeAttributes(btn, ["disabled"]);
        }
    }

    function getLocalData () {
        alert(UE.getEditor('editor').execCommand( "getlocaldata" ));
    }

    function clearLocalData () {
        UE.getEditor('editor').execCommand( "clearlocaldata" );
        alert("已清空草稿箱")
    }

function activate_promotion_input(should_activate){
 		if(should_activate==1){
			$("#promotion_price").attr("disabled",false);
			$("#promotion_start").attr("disabled",false);
			$("#promotion_end").attr("disabled",false);
			return;
		}
			$("#promotion_price").attr("disabled",true);
			$("#promotion_start").attr("disabled",true);
			$("#promotion_end").attr("disabled",true);
		
	}
	
	function show_market_price(){
	var market_price_float=(parseFloat($("#price").val())*1.15).toFixed(2);
	$("#market_price").val(market_price_float);
}
	
</script>
</body>
</html>