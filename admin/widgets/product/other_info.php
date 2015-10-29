<table width="100%" align="center" class="phpshop123_form_box">
<tr valign="baseline">
      <td nowrap align="right">重量：</td>
      <td valign="baseline"><input name="weight" type="text"  id="weight" value="<?php echo $row_product['weight']; ?>" size="32" maxlength="13" />克</td>
    </tr>
	<tr valign="baseline">
      <td nowrap align="right">库存:</td>
      <td><input name="store_num" type="text" id="store_num" value="<?php echo $row_product['store_num']; ?>" size="32" maxlength="11"></td>
    </tr>
<tr valign="baseline">
      <td nowrap align="right">上架:</td>
      <td valign="baseline"><input type="radio" name="is_on_sheft" value="1" <?php if (!(strcmp($row_product['is_on_sheft'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_on_sheft" value="0" <?php if (!(strcmp($row_product['is_on_sheft'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">热门商品:</td>
      <td valign="baseline"><input type="radio" name="is_hot" value="1" <?php if (!(strcmp($row_product['is_hot'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_hot" value="0" <?php if (!(strcmp($row_product['is_hot'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">当季商品:</td>
      <td valign="baseline"><input type="radio" name="is_season" value="1" <?php if (!(strcmp($row_product['is_season'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_season" value="0" <?php if (!(strcmp($row_product['is_season'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">推荐商品:</td>
      <td valign="baseline"><input type="radio" name="is_recommanded" value="1" <?php if (!(strcmp($row_product['is_recommanded'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_recommanded" value="0" <?php if (!(strcmp($row_product['is_recommanded'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">免运费:</td>
      <td><input type="radio" name="is_shipping_free" value="1" <?php if (!(strcmp($row_product['is_shipping_free'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_shipping_free" value="0" <?php if (!(strcmp($row_product['is_shipping_free'],"0"))) {echo "CHECKED";} ?> />
否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">meta关键词:</td>
      <td><label>
        <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo $row_product['meta_keywords']; ?>" />
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">meta介绍:</td>
      <td><input type="text" name="meta_desc" id="meta_desc" value="<?php echo $row_product['meta_desc']; ?>"/></td>
    </tr>
</table>