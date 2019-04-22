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
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_user.css" rel="stylesheet" type="text/css" />
</head>

<body>

  <?php if ($totalRows_comments > 0) { // Show if recordset not empty ?>
    <p class="phpshop123_user_title">评论搜索</p>
    <form id="comment_search" name="comment_search" method="get" action="">
      <table width="100%" border="0" class="phpshop123_user_form_box">
        <tr>
          <td>评论内容</td>
          <td><input name="message" type="text" id="message" maxlength="32" /></td>
          <td><label>
            <div align="right">
              <input type="submit" name="Submit" value="搜索" />
              </div>
          </label></td>
        </tr>
      </table>
    </form>
    <?php } // Show if recordset not empty ?><p class="phpshop123_user_title">评论列表</p>
  <?php if ($totalRows_comments > 0) { // Show if recordset not empty ?>
  <table width="100%" border="0" align="center" class="phpshop123_user_list_box">
    <tr>
      <td width="87%">评论</td>
      <td width="13%">发布时间</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="/product.php?id=<?php echo $row_comments['product_id']; ?>#comment" target="_blank"><?php echo $row_comments['message']; ?></a></td>
        <td><?php echo $row_comments['create_time']; ?>&nbsp; </td>
      </tr>
      <?php } while ($row_comments = mysqli_fetch_assoc($comments)); ?>
  </table>
  <br>
  <table border="0" width="50%" align="right">
    <tr>
      <td width="23%" align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, 0, $queryString_comments); ?>" class="phpshop123_user_paging">第一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="31%" align="center"><?php if ($pageNum_comments > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, max(0, $pageNum_comments - 1), $queryString_comments); ?>" class="phpshop123_user_paging">前一页</a>
            <?php } // Show if not first page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, min($totalPages_comments, $pageNum_comments + 1), $queryString_comments); ?>" class="phpshop123_user_paging">下一页</a>
            <?php } // Show if not last page ?>      </td>
      <td width="23%" align="center"><?php if ($pageNum_comments < $totalPages_comments) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_comments=%d%s", $currentPage, $totalPages_comments, $queryString_comments); ?>" class="phpshop123_user_paging">最后一页</a>
            <?php } // Show if not last page ?>      </td>
    </tr>
  </table>
  记录 <?php echo ($startRow_comments + 1) ?> 到 <?php echo min($startRow_comments + $maxRows_comments, $totalRows_comments) ?> (总共 <?php echo $totalRows_comments ?>)  
  <?php } // Show if recordset not empty ?>
  <?php if ($totalRows_comments == 0) { // Show if recordset empty ?>
    <p class="phpshop123_user_title">暂无评论！</p>
    <?php } // Show if recordset empty ?></body>
</html>