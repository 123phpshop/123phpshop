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
 ?><table width="100%" align="center" class="phpshop123_form_box">
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
      <td valign="baseline"><input type="radio" name="is_on_sheft" value="1" <?php if (isset($row_product['is_on_sheft']) && !(strcmp($row_product['is_on_sheft'],"1")) ) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_on_sheft" value="0" <?php if (!isset($row_product['is_on_sheft']) || !(strcmp($row_product['is_on_sheft'],"0"))) {echo "CHECKED";} ?> />
  否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">热门商品:</td>
      <td valign="baseline"><input type="radio" name="is_hot" value="1" <?php if (isset($row_product['is_hot']) &&!(strcmp($row_product['is_hot'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_hot" value="0" <?php if (!isset($row_product['is_hot']) || !(strcmp($row_product['is_hot'],"0")) || !isset($row_product['is_hot'])) {echo "CHECKED";} ?> /><span style="color:#999999">
  否 [<a href="http://www.123phpshop.com/product.php?id=25" target="_blank" style="color:#999999">购买热门产品挂件</a>]</span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">当季商品:</td>
      <td valign="baseline"><span style="color:#999999"><input type="radio" name="is_season" value="1" <?php if (isset($row_product['is_season']) && !(strcmp($row_product['is_season'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_season" value="0" <?php if (!isset($row_product['is_season']) || !(strcmp($row_product['is_season'],"0")) || !isset($row_product['is_season'])) {echo "CHECKED";} ?> />
  否 [<a href="http://www.123phpshop.com/product.php?id=23" target="_blank" style="color:#999999">购买当季产品挂件</a>]</span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">推荐商品:</td>
      <td valign="baseline"><span style="color:#999999"><input type="radio" name="is_recommanded" value="1" <?php if (isset($row_product['is_hot']) && !(strcmp($row_product['is_recommanded'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_recommanded" value="0" <?php if (!isset($row_product['is_recommanded']) || !(strcmp($row_product['is_recommanded'],"0")) || !isset($row_product['is_recommanded'])) {echo "CHECKED";} ?> />
  否 [<a href="http://www.123phpshop.com/product.php?id=24" target="_blank"  style="color:#999999">购买推荐产品挂件</a>]</span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">免运费:</td>
      <td><input type="radio" name="is_shipping_free" value="1" <?php if (isset($row_product['is_shipping_free']) && !(strcmp($row_product['is_shipping_free'],"1"))) {echo "CHECKED";} ?> />
是
  <input type="radio" name="is_shipping_free" value="0" <?php if (!isset($row_product['is_shipping_free']) || !(strcmp($row_product['is_shipping_free'],"0")) || !isset($row_product['is_shipping_free'])) {echo "CHECKED";} ?> />
否</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">meta关键词:</td>
      <td><label>
        <input type="text" name="meta_keywords" id="meta_keywords" value="<?php echo isset($row_product['meta_keywords'])?$row_product['meta_keywords']:""; ?>" />
      </label></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap>meta介绍:</td>
      <td><textarea name="meta_desc" cols="50" rows="4" id="meta_desc"><?php echo isset($row_product['meta_desc'])?$row_product['meta_desc']:""; ?></textarea></td>
    </tr>
</table>