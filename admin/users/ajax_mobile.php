<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015~2019 上海序程信息科技有限公司，并保留所有权利。
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
$doc_url="ad.html#list";
$support_email_question="广告列表";
$result="true";
$colname_member = "-1";
if (isset($_POST['mobile'])) {
  $colname_member = (get_magic_quotes_gpc()) ? $_POST['mobile'] : addslashes($_POST['mobile']);
}
mysql_select_db($database_localhost, $localhost);
$query_member = sprintf("SELECT * FROM user WHERE mobile = '%s'", $colname_member);
$member = mysql_query($query_member, $localhost) ;
if(!$member){$logger->fatal("数据库操作失败:".$query_member);}
$row_member = mysql_fetch_assoc($member);
$totalRows_member = mysql_num_rows($member);
if($totalRows_member>0){
	$result="false";
}
die($result);
?>