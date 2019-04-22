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
 *  作者:    123PHPSHOP团队
 *  手机:    13391334121
 *  邮箱:    service@123phpshop.com
 */
?><?php
require_once '../../Connections/localhost.php';
$doc_url = "ad.html#delete";
$support_email_question = "删除广告";
$could_delete = 1;
$colname_news = "-1";
log_admin("删除广告");
if (isset($_GET['id'])) {
    $colname_news = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_localhost, $localhost);
$query_news = sprintf("SELECT * FROM ad WHERE id = %s", $colname_news);
$news = mysqli_query($localhost,$query_news);
if (!$news) {
    $logger->fatal(__FILE__ . ":数据库操作失败:" . mysqli_error($localhost) . $query_news);
}
$row_news = mysqli_fetch_assoc($news);
$totalRows_news = mysql_num_rows($news);

try {

    if ($totalRows_news == 0) { // 如果id不存在
        throw new Exception("广告不存在！");
    }

    // 检查下面是否有图片，如果有图片的话，那么请先删除图片然后再试
    $colname_ad_images = "-1";
    if (isset($_GET['id'])) {
        $colname_ad_images = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
    }
    // 选择数据库
    mysql_select_db($database_localhost, $localhost);
    $query_ad_images = sprintf("SELECT * FROM ad_images WHERE ad_id = %s", $colname_ad_images);
    $ad_images = mysqli_query($localhost,$query_ad_images);
    if (!$ad_images) {
        $logger->fatal("数据库操作失败:" . $query_ad_images);
        throw new Exception("数据库操作失败:" . $query_ad_images);
    }

    $totalRows_ad_images = mysql_num_rows($ad_images);

    if ($totalRows_ad_images > 0) {
        throw new Exception("此广告下面有图片，请先删除图片再进行操作");
    }

    // 正式删除
    $update_catalog = sprintf("delete from `ad` where id = %s", $colname_news);
    $update_catalog_query = mysqli_query($localhost,$update_catalog);
    if (!$update_catalog_query) {
        $logger->fatal("删除广告操作失败:" . $updateSQL);
        throw new Exception(COMMON_LANG_SYSTEM_ERROR_PLEASE_TRY_AGAIN_LATER);
    }
    // 删除之后的跳转
    $remove_succeed_url = "index.php";
    header("Location: " . $remove_succeed_url);
} catch (Exception $ex) {
    $error = $ex->getMessage();
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
	<div class="phpshop123_infobox">
		<div id="doc_help"
			style="display: inline; height: 40px; line-height: 50px; color: #CCCCCC;">
			<a style="color: #CCCCCC; margin-left: 3px;" target="_blank"
				href="<?php echo isset($doc_url) ? "http://www.123phpshop.com/doc/v1.5/" . $doc_url : "http://www.123phpshop.com/doc/"; ?>">[文档]</a><a
				style="color: #CCCCCC; margin-left: 3px;" target="_blank"
				href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes">[人工支持]</a><a
				href=mailto:service@123phpshop.com?subject=我在
				<?php echo $support_email_question; ?> 的时候遇到了问题，请支持
				style="color: #CCCCCC; margin-left: 3px;">[邮件支持]</a>
		</div>
  <?php if (!empty($error)) {?>
  <p>由于以下原因，您不能删除这条广告，请及时修正，或是联系123phpshop.com的技术支持人员！</p>
		<p><?php echo $error ?></p> 
		<p>
			您也可以<a href="index.php">点击这里返回</a>。
		</p>
  <?php }?>
</div>

</body>
</html>