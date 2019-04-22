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
<style type="text/css">
<!--
body {
	background-color: #f5f5f5;
}
-->
</style>
<link href="/css/common_admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p class="phpshop123_title">更新收货地址</p>
 

<form method="post" name="form1" id="update_consignee_form" action="<?php echo $editFormAction; ?>">
  <table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">姓名:</td>
      <td><input type="text" name="name" value="<?php echo $row_consignee['name']; ?>" size="32">*为保证收货顺利，请使用真实姓名</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">手机:</td>
      <td><input type="text" name="mobile" value="<?php echo $row_consignee['mobile']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">省市:</td>
      <td><select name="province"  id="province"  ></select><select name="city"  id="city"  ></select><select name="district"  id="district"  ></select>      </td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right">地址:</td>
      <td><input type="text" name="address" value="<?php echo $row_consignee['address']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">邮编:</td>
      <td><input type="text" name="zip" value="<?php echo $row_consignee['zip']; ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="更新"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_consignee['id']; ?>">
</form>
<script language="JavaScript" type="text/javascript" src="../../js/jquery-1.7.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="../../js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="/widget/area/jsAddress.js"></script>
<script>
$().ready(function(){
addressInit('province', 'city', 'district', '<?php echo $row_consignee['province']; ?>', '<?php echo $row_consignee['city']; ?>', '<?php echo $row_consignee['district']; ?>');
 	$("#update_consignee_form").validate({
        rules: {
		
            name: {
                required: true,
				minlength: 2,
             },
            mobile: {
                required: true,
                minlength: 11,
				digits:true   
            },
            address: {
                required: true,
                minlength: 3   
            },
 			zip: {
                required: true,
                minlength: 6,
				digits:true
            }
        } 
    });
});</script>
</body>
</html>