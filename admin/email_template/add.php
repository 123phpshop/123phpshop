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
 ?><?php require_once('../../Connections/localhost.php'); ?>
<?php
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
$doc_url="email_template.html#add";
$support_email_question="添加邮件模板";
$error="";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	// 一个发送时间只能有一个模板，所以这里需要检查是否这个发送实际已经有相应的模板了，如果有的话，那么就放弃
	$colname_product = "-1";
	if (isset($_POST['code'])) {
	  $colname_product = (get_magic_quotes_gpc()) ? $_POST['code'] : addslashes($_POST['code']);
	}

	 mysql_select_db($database_localhost, $localhost);
	$query_product = sprintf("SELECT * FROM email_templates WHERE code = '%s' and is_delete=0", trim($colname_product));
	$product = mysql_query($query_product, $localhost) or die(mysql_error());
	$row_product = mysql_fetch_assoc($product);
	$totalRows_product = mysql_num_rows($product);
	if($totalRows_product>0){
		$error="一个【发送时间】只能有添加一个模板哦";
	}else{

	
  $insertSQL = sprintf("INSERT INTO email_templates (name, code, title, content) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['code'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['content'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form method="post" name="form1" id="form1" action="<?php echo $editFormAction; ?>">
   <span class="phpshop123_title">添加邮件模板</span>
    <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
  
    <?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/_error.php");?>
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">模板名称:</td>
      <td><input name="name" type="text" id="name"  value="" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">发送时间:</td>
      <td><select name="code" id="code">
	  	<?php foreach($global_phpshop123_email_send_time as $key=>$value){ ?>
        <option value="<?php echo $key;?>" ><?php echo $value;?></option>
		<?php } ?>
       </select>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">标题:</td>
      <td><input name="title" type="text" id="title"  value="" size="32" maxlength="50"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">内容:</td>
      <td><textarea name="content" id="content" cols="100" rows="20"></textarea>
      </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="添加"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<script language="JavaScript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script>
$().ready(function(){
 	$("#form1").validate({
        rules: {
             name: {
                required: true,
				minlength: 2,
				maxlength: 32
             },
				remote:{
                    url: "_ajax_code.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        'code': function(){return $("#code").val();}
                    }
				},
            title: {
                required: true,
  				maxlength: 50   
            },
            content: {
                required: true 
            }
        } 
    });
});</script>
</body>
</html>