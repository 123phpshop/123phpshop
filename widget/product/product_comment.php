<?php
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码以任何形式任何目的的再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/lib/product.php'); ?>
<?php
$colname_product = "-1";
if (isset($_GET['id'])) {
  $colname_product = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_comment") && $colname_product!='-1' && isset($_SESSION['user_id']) && user_could_comment($_SESSION['user_id'],$colname_product)) {
  $insertSQL = sprintf("INSERT INTO product_comment (message, product_id, user_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['message'], "text"),
                       GetSQLValueString($colname_product, "text"),
                       GetSQLValueString($_SESSION['user_id'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
  
	//	  这里还需要将评论+1
	$update_product_sql="update product set commented_num=commented_num+1 where id=$colname_product";
	mysql_select_db($database_localhost, $localhost);
	mysql_query($update_product_sql, $localhost) or die(mysql_error());
}


$colname_comments = "-1";
if (isset($_GET['id'])) {
  $colname_comments = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_comments = sprintf("SELECT product_comment.*,user.username FROM product_comment inner join user on user.id=product_comment.user_id WHERE product_comment.product_id = %s and product_comment.is_delete=0 ORDER BY product_comment.id DESC", $colname_comments);
$comments = mysql_query($query_comments, $localhost) or die(mysql_error());
$row_comments = mysql_fetch_assoc($comments);
$totalRows_comments = mysql_num_rows($comments);
?>


<?php if ($totalRows_comments > 0) { ?>
	<style>
		#comment_list{
			font-size:14px;
		}
	</style>
<table width="990" height="31" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
	 <br />
	<table style="background-color:white;border-top:2px solid red;border-bottom-width:0px" width="105" height="33" border="1" cellpadding="0" cellspacing="0" bordercolor="#DEDFDE">
      <tr>
        <td><div align="center">评价列表</div></td>
      </tr>
    </table></td>
    <td><br /><table  style="border-bottom:1px solid #DEDFDE " width="885" height="31" border="0">
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<br />

<table width="990" border="1" style="border-bottom-width:0;" align="center" cellpadding="0" cellspacing="0" bordercolor="#ddd">
  <tr>
    <td><table style="color:#666666;" width="100%" height="31" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr bordercolor="#ddd" bgcolor="#f7f7f7">
        <th scope="col">评论内容</th>
        <th width="135" height="32" bgcolor="#f7f7f7" scope="col">评论人</th>
      </tr>
    </table></td>
  </tr>
</table>
           <?php do { ?>
		  <div style="width:988px;margin:auto 0;border:1px solid #ddd;border-top-width:0px;">
		  <table width="100%" style="font-family:宋体;font-size:12px;margin:0px auto;border-collapse:collapse;" border="0" id="comment_list" align="center" cellpadding="0" cellspacing="0" bordercolor="#ddd"   >
          <tr>
            <td height="159" style="padding-left:20px;"><?php echo $row_comments['message']; ?></td>
            <td width="135" height="159" style="padding-left:21px;"><p  align="left"><?php echo $row_comments['username']; ?></p>
              <div align="left"><?php echo $row_comments['create_time']; ?></div></td>
          </tr>
		  </table>
		  </div>
          <?php } while ($row_comments = mysql_fetch_assoc($comments)); ?>
  
<?php } ?>
           <?php if($colname_product!='-1' && isset($_SESSION['user_id']) && user_could_comment($_SESSION['user_id'],$colname_product)){?>
                </p>
     <form action="<?php echo $editFormAction; ?>" method="post" name="new_comment">
<table width="990" align="center" style="margin:0px auto;" >
            <tr valign="baseline">
              <td>&nbsp;</td>
            </tr>
            <tr valign="baseline">
              <td><div align="left">
                  <textarea name="message" cols="100" rows="10"></textarea>
              </div></td>
            </tr>
            <tr valign="baseline">
              <td><div align="left">
                  <input name="submit" type="submit" value="发表评论" />
              </div></td>
            </tr>
  </table>
            <input type="hidden" name="MM_insert" value="new_comment" />
</form>
   
<?php } 
mysql_free_result($comments);
?>
