<table width="100%" align="center" class="phpshop123_form_box">
<tr valign="baseline">
      <td nowrap align="right">重量：</td>
      <td valign="baseline"><input name="weight" type="text"  id="weight" value="<?php echo isset($row_product['weight'])?$row_product['weight']:"0"; ?>" size="32" maxlength="13" />克</td>
    </tr>
	<tr valign="baseline">
      <td nowrap align="right">库存:</td>
      <td><input name="store_num" type="text" id="store_num" value="<?php echo isset($row_product['store_num'])?$row_product['store_num']:"1000"; ?>" size="32" maxlength="11"></td>
    </tr>
<tr valign="baseline">
      <td nowrap align="right">上架:</td>
      <td valign="baseline"><input type="radio" name="is_on_sheft" value="1" <?php if (!(strcmp($row_product['is_on_sheft'],"1")) || !isset($row_product['is_on_sheft'])) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_on_sheft" value="0" <?php if (!(strcmp($row_product['is_on_sheft'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">热门商品:</td>
      <td valign="baseline"><input type="radio" name="is_hot" value="1" <?php if (!(strcmp($row_product['is_hot'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_hot" value="0" <?php if (!(strcmp($row_product['is_hot'],"0")) || !isset($row_product['is_hot'])) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">当季商品:</td>
      <td valign="baseline"><input type="radio" name="is_season" value="1" <?php if (!(strcmp($row_product['is_season'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_season" value="0" <?php if (!(strcmp($row_product['is_season'],"0")) || !isset($row_product['is_season'])) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">推荐商品:</td>
      <td valign="baseline"><input type="radio" name="is_recommanded" value="1" <?php if (!(strcmp($row_product['is_recommanded'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_recommanded" value="0" <?php if (!(strcmp($row_product['is_recommanded'],"0")) || !isset($row_product['is_recommanded'])) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">免运费:</td>
      <td><input type="radio" name="is_shipping_free" value="1" <?php if (!(strcmp($row_product['is_shipping_free'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_shipping_free" value="0" <?php if (!(strcmp($row_product['is_shipping_free'],"0")) || !isset($row_product['is_shipping_free'])) {echo "CHECKED";} ?> />
否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">meta关键词:</td>
      <td><label>
        <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo $row_product['meta_keywords']; ?>" />
      </label></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap>meta介绍:</td>
      <td><textarea name="meta_desc" cols="50" rows="4" id="meta_desc"><?php echo $row_product['meta_desc']; ?></textarea></td>
    </tr>
</table>