<?php require_once('../../Connections/localhost.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE email_templates SET name=%s, code=%s, title=%s, content=%s WHERE id=%s",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['code'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['content'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_email_template = "-1";
if (isset($_GET['id'])) {
  $colname_email_template = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_email_template = sprintf("SELECT * FROM email_templates WHERE id = %s", $colname_email_template);
$email_template = mysql_query($query_email_template, $localhost) or die(mysql_error());
$row_email_template = mysql_fetch_assoc($email_template);
$totalRows_email_template = mysql_num_rows($email_template);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($totalRows_email_template > 0) { // Show if recordset not empty ?>
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <p class="phpshop123_title">更新邮件模板</p>
    <table align="center" class="phpshop123_form_box">
      <tr valign="baseline">
        <td nowrap align="right">Name:</td>
        <td><input type="text" name="name" value="<?php echo $row_email_template['name']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">Code:</td>
        <td><select name="code">
			<?php foreach($global_phpshop123_email_send_time as $key=>$value){ ?>
          	<option value="<?php echo $key;?>" <?php if ($key==$row_email_template['code']) {echo "SELECTED";} ?>><?php echo $value;?></option>
		  <?php } ?>
           </select>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">Title:</td>
        <td><input type="text" name="title" value="<?php echo $row_email_template['title']; ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right" valign="top">Content:</td>
        <td><textarea name="content" cols="50" rows="5"><?php echo $row_email_template['content']; ?></textarea>        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">&nbsp;</td>
        <td><input type="submit" value="更新记录"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id" value="<?php echo $row_email_template['id']; ?>">
      </form>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_email_template == 0) { // Show if recordset empty ?>
  <p class="phpshop123_infobox">邮件模板不存在</p>
  <?php } // Show if recordset empty ?></body>
</html>
<?php
mysql_free_result($email_template);
?>
