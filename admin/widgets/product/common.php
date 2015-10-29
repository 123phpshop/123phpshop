<table align="center" class="phpshop123_form_box">
    <tr valign="baseline">
      <td nowrap align="right">名称:</td>
      <td><input name="name" type="text" id="name" value="<?php echo $row_product['name']; ?>" size="32" maxlength="50"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">广告语:</td>
      <td><input name="ad_text" type="text" id="ad_text"  value="<?php echo $row_product['ad_text']; ?>" size="32" maxlength="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">价格:</td>
      <td><input name="price" type="text" id="price" value="<?php echo $row_product['price']; ?>" size="32" maxlength="13"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">市场价:</td>
      <td><input name="market_price" type="text"  id="market_price" value="<?php echo $row_product['market_price']; ?>" size="32" maxlength="13">	</td>
    </tr>
	<tr valign="baseline">
      <td nowrap align="right">品牌:</td>
      <td><select name="brand_id" id="brand_id">
        <option value="0" <?php if (!(strcmp(0, $row_product['brand_id']))) {echo "selected=\"selected\"";} ?>>未设置</option>
        <?php
do {  
?>
        <option value="<?php echo $row_brands['id']?>"<?php if (!(strcmp($row_brands['id'], $row_product['brand_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_brands['name']?></option>
        <?php
} while ($row_brands = mysql_fetch_assoc($brands));
  $rows = mysql_num_rows($brands);
  if($rows > 0) {
      mysql_data_seek($brands, 0);
	  $row_brands = mysql_fetch_assoc($brands);
  }
?>
      </select></td>
    </tr>
	
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">商品类型：</td>
      <td><select name="product_type_id" id="product_type_id">
        <option value="0" <?php if (!(strcmp(0, $row_product['product_type_id']))) {echo "selected=\"selected\"";} ?>>未设置</option>
        <?php
do {  
?>
        <option value="<?php echo $row_product_types['id']?>"<?php if (!(strcmp($row_product_types['id'], $row_product['product_type_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_product_types['name']?></option>
        <?php
} while ($row_product_types = mysql_fetch_assoc($product_types));
  $rows = mysql_num_rows($product_types);
  if($rows > 0) {
      mysql_data_seek($product_types, 0);
	  $row_product_types = mysql_fetch_assoc($product_types);
  }
?>
      </select></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right">单位：</td>
      <td valign="baseline"><input name="unit" type="text"  id="unit" value="<?php echo $row_product['unit']; ?>" size="32" maxlength="13" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">虚拟物品：</td>
      <td valign="baseline"><input type="radio" name="is_virtual" value="1" <?php if (!(strcmp($row_product['is_virtual'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_virtual" value="0" <?php if (!(strcmp($row_product['is_virtual'],"0"))) {echo "CHECKED";} ?> />
否</td>
    </tr> 
    
    <tr valign="baseline">
      <td nowrap align="right">备注:</td>
      <td><input type="text" name="description" id="description" value="<?php echo $row_product['description']; ?>"/></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right" valign="top">标签：</td>
      <td><label>
        <input name="tags"  type="text" id="tags" size="32" value="<?php echo $row_product['tags']; ?>" maxlength="50" />
      [2个标签之间请以空格隔开]</label></td>
    </tr>
   
   
  </table>