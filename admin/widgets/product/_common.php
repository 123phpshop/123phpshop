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
 ?><table align="center" class="phpshop123_form_box">
	<?php if(!isset($_GET['catalog_id'])){ ?>
	 <tr valign="baseline">
	   <td nowrap align="right">同步到:</td>
	   <td><label>
	     <input type="checkbox" name="checkbox" value="checkbox" />
	   </label>
	     新浪微博
	     <label>
	     <input type="checkbox" name="checkbox2" value="checkbox" />
	     腾讯微博</label>
	     <label>
	     <input type="checkbox" name="checkbox3" value="checkbox" />
	     朋友圈<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes";?>[购买此功能]</a></div>
	     </label></td>
  </tr>
	 <tr valign="baseline">
	   <td nowrap align="right">同步频率:</td>
	   <td><label>
	     <input type="radio" name="radiobutton" value="radiobutton" />
       马上同步
       <input type="radio" name="radiobutton" value="radiobutton" />
       上架后同步
       <input type="radio" name="radiobutton" value="radiobutton" />
       每
       <input name="textfield" type="text" value="2" size="2" />
       天同步一次<div id="doc_help" style="display:inline;height:40px;line-height:50px;color:#CCCCCC;"><a style="color:#CCCCCC;margin-left:3px;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=1718101117&site=qq&menu=yes";?>[购买此功能]</a></div></label></td>
  </tr>
	 <tr valign="baseline">
      <td nowrap align="right">分类:</td>
      <td><?php include_once($_SERVER['DOCUMENT_ROOT']."/admin/widgets/product/catalogs_menu.php");?>      <a href="/admin/catalog/index.php"><u>添加分类</u></a>*</td>
    </tr>
	<?php } ?>
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input name="name" type="text" id="name" value="<?php echo isset($row_product['name'])?$row_product['name']:""; ?>" size="32" maxlength="50">
      *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">广告语:</td>
      <td><input name="ad_text" type="text" id="ad_text"  value="<?php echo isset($row_product['ad_text'])?$row_product['ad_text']:""; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">价格:</td>
      <td><input name="price" type="text" id="price" onInput="show_market_price()" value="<?php echo isset($row_product['price'])?$row_product['price']:""; ?>" size="32" maxlength="13">
      *</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">市场价:</td>
      <td><input name="market_price" type="text"  id="market_price" value="<?php echo isset($row_product['market_price'])?$row_product['market_price']:""; ?>" size="32" maxlength="13">
      [自动设置为比价格贵15%]* 	</td>
    </tr>
	<tr valign="baseline">
	  <td nowrap align="right">消费级分数：</td>
	  <td> 
	    <input type="text" name="consumption_pointers" value="<?php echo isset($row_product['consumption_pointers'])?$row_product['consumption_pointers']:"-1"; ?>" />
	   [购买该商品时赠送的消费积分数,如果设置为-1表示系统将会按照商品价格赠送，例如5元的商品将会赠送消费积分5分]</td>
  </tr>
	<tr valign="baseline">
	  <td nowrap align="right">用户等级积分数：</td>
	  <td><input type="text" name="user_level_pointers" value="<?php echo isset($row_product['user_level_pointers'])?$row_product['user_level_pointers']:"-1"; ?>" />[购买该商品时赠送的等级积分数,如果设置为-1表示系统将会按照商品价格赠送，例如5元的商品将会赠送用户等级积分5分]</td>
  </tr>
	<tr valign="baseline">
      <td nowrap align="right">品牌:</td>
      <td><select name="brand_id" id="brand_id">
        <option value="0" <?php if (isset($row_product['product_type_id']) && (!(strcmp(0, $row_product['brand_id'])) || !isset($row_product['brand_id']))) {echo "selected=\"selected\"";} ?>>未设置</option>
        <?php
do {  
?>
        <option value="<?php echo $row_brands['id']?>"<?php if (isset($row_product['product_type_id']) && (!(strcmp($row_brands['id'], $row_product['brand_id'])))) {echo "selected=\"selected\"";} ?>><?php echo $row_brands['name']?></option>
        <?php
} while ($row_brands = mysql_fetch_assoc($brands));
  $rows = mysql_num_rows($brands);
  if($rows > 0) {
      mysql_data_seek($brands, 0);
	  $row_brands = mysql_fetch_assoc($brands);
  }
?>
      </select>        <a href="/admin/brands/add.php"><u>添加品牌</u></a></td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">商品类型：</td>
      <td><select name="product_type_id" id="product_type_id">
        <option value="0" <?php if (isset($row_product['product_type_id']) && !(strcmp(0, $row_product['product_type_id']))) {echo "selected=\"selected\"";} ?>>未设置</option>
        <?php
do {  
?>
        <option value="<?php echo $row_product_types['id']?>"<?php if (isset($row_product['product_type_id']) && !(strcmp($row_product_types['id'], $row_product['product_type_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_product_types['name']?></option>
        <?php
} while ($row_product_types = mysql_fetch_assoc($product_types));
  $rows = mysql_num_rows($product_types);
  if($rows > 0) {
      mysql_data_seek($product_types, 0);
	  $row_product_types = mysql_fetch_assoc($product_types);
  }
?>
      </select>      <a href="/admin/product_type/index.php"><u>添加商品类型</u></a></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right">单位：</td>
      <td valign="baseline"><input name="unit" type="text"  id="unit" value="<?php echo isset($row_product['unit'])?$row_product['unit']:""; ?>" size="32" maxlength="13" />
      * </td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">虚拟物品</td>
      <td valign="baseline"><input type="radio" name="is_virtual" value="1" <?php if (( isset($is_vproduct_add_page) && !isset($row_product['is_virtual'])) || !(strcmp($row_product['is_virtual'],"1")) || isset($is_vproduct_add_page)) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_virtual" value="0" <?php if (( !isset($is_vproduct_add_page) && (!(strcmp($row_product['is_virtual'],"0")) || !isset($row_product['is_virtual'])))) {echo "CHECKED";} ?> />
否</td>
    </tr> 
	<!--tr valign="baseline">
      <td nowrap align="right">赠送点数:</td>
      <td><input type="text" name="pointers" id="pointers" value="<?php echo isset($row_product['pointers'])?$row_product['pointers']:-1; ?>"/>
      [默认是-1，也就是按照商品价格提供点数，如果商品的价格是小数，那么取上值，例如3.1元会给予4个积分]*</td>
    </tr-->
	
	<tr valign="baseline">
      <td nowrap align="right">优惠产品:</td>
      <td><input type="radio" name="is_promotion" value="1" onClick="activate_promotion_input(1)" <?php if (isset($row_product['is_promotion']) && !(strcmp($row_product['is_promotion'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_promotion" value="0" onClick="activate_promotion_input(-1)" <?php if (!isset($row_product['is_promotion'])  || !(strcmp($row_product['is_promotion'],"0")) || !isset($row_product['is_promotion'])) {echo "CHECKED";} ?> />
  否    </tr>
	
	<tr valign="baseline">
      <td nowrap align="right">优惠价:</td>
      <td>
      <input name="promotion_price" type="text" id="promotion_price" value="<?php echo isset($row_product['promotion_price'])?$row_product['promotion_price']:""; ?>"  maxlength="50" <?php if (!isset($row_product['is_promotion'])  || !(strcmp($row_product['is_promotion'],"0")) || !isset($row_product['is_promotion'])) {echo 'disabled="true"';} ?>/></td>
    </tr>
	
	<tr valign="baseline">
      <td nowrap align="right">优惠开始时间:</td>
      <td><input name="promotion_start" type="text" id="promotion_start" value="<?php echo isset($row_product['promotion_start'])?$row_product['promotion_start']:""; ?>" maxlength="10" <?php if (!isset($row_product['is_promotion'])  || !(strcmp($row_product['is_promotion'],"0")) || !isset($row_product['is_promotion'])) {echo 'disabled="true"';} ?>/></td>
    </tr>
 	
	<tr valign="baseline">
      <td nowrap align="right">优惠结束时间:</td>
      <td><input name="promotion_end" type="text" id="promotion_end" value="<?php echo isset($row_product['promotion_end'])?$row_product['promotion_end']:""; ?>" maxlength="10" <?php if (!isset($row_product['is_promotion'])  || !(strcmp($row_product['is_promotion'],"0")) || !isset($row_product['is_promotion'])) {echo 'disabled="true"';} ?>/></td>
    </tr>
	
    
    <tr valign="baseline">
      <td nowrap align="right">备注:</td>
      <td><input type="text" name="description" id="description" value="<?php echo isset($row_product['description'])?$row_product['description']:""; ?>"/></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right" valign="top">标签：</td>
      <td> 
        <input name="tags"  type="text" id="tags" size="32" value="<?php echo isset($row_product['tags'])?$row_product['tags']:""; ?>" maxlength="50" />
      *	[2个标签之间请以空格隔开]</td>
    </tr>
    </table>
