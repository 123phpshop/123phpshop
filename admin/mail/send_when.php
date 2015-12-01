<?php require_once('../../Connections/localhost.php'); ?>
<?php
$doc_url="mail.html#send_when";
$support_email_question="设置邮件发送时间";

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE shop_info SET send_when=%s WHERE id=%s",
                       GetSQLValueString(isset($_POST['send_when'])?implode(",",$_POST['send_when']):"","text"),
                       GetSQLValueString(1,"int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
}

$send_when_array=array();
mysql_select_db($database_localhost, $localhost);
$query_send_when = "SELECT id, send_when FROM shop_info WHERE id = 1 and send_when is not null";
$send_when = mysql_query($query_send_when, $localhost) or die(mysql_error());
$row_send_when = mysql_fetch_assoc($send_when);
$totalRows_send_when = mysql_num_rows($send_when);
if($totalRows_send_when>0){
 	$send_when_array=explode(",",$row_send_when["send_when"]);
}
 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<span class="phpshop123_title">邮件发送设置</span>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <table width="200" border="0" class="phpshop123_form_box">
  	<?php foreach($global_phpshop123_email_send_time as $key=>$value){ ?>
    <tr>
      <td width="4%"> 
        <input type="checkbox" name="send_when[]" value="<?php echo $key;?>" <?php if(in_array((string)$key,$send_when_array)){ ?> checked <?php } ?>/>
      </td>
      <td width="96%"><?php echo $value;?></td>
	  <?php } ?>
    </tr>
  </table>
  <p>
    <label>
    <input type="submit" name="Submit" value="提交" />
    </label>
  </p>
  <input type="hidden" name="MM_update" value="form1">
</form>
<p>&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($send_when);
?>
