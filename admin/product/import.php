<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="../../css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">导入商品</p><?php include($_SERVER['DOCUMENT_ROOT']."/admin/widgets/dh.php");?>
<form action="" method="post" enctype="multipart/form-data" name="import_goods_form" id="import_goods_form">
  <input type="file" name="file" />
  <label>
  <input name="导入" type="submit" id="导入" value="提交" />
  </label>
  <p>&nbsp;</p>
  <p>提示：导入的商品文件需要为csv格式，且字段要求如下：</p>
  <table width="100%" border="1">
    <tr>
      <td>名称</td>
      <td>广告词</td>
      <td>分类</td>
      <td>类型</td>
      <td>品牌 </td>
      <td>是否免运费</td>
      <td>重量</td>
      <td>meta关键词</td>
      <td>meta描述</td>
      <td>是否为虚拟物品</td>
      <td>介绍</td>
      <td>价格</td>
      <td>是否优惠</td>
      <td>优惠价格</td>
      <td>优惠起始时间</td>
      <td>优惠结束时间</td>
      <td>市场价</td>
      <td>单位</td>
      <td>是否为虚拟物品</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<p>&nbsp; </p>
</body>
</html>
