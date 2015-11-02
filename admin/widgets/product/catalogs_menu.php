<?php
mysql_select_db($database_localhost, $localhost);
$query_catalogs = "SELECT * FROM `catalog`";
$catalogs = mysql_query($query_catalogs, $localhost) or die(mysql_error());
$row_catalogs = mysql_fetch_assoc($catalogs);
$totalRows_catalogs = mysql_num_rows($catalogs);
?>
<select name="catalog_id" id="catalog_id">
  <?php
do {  
?>
  <option value="<?php echo $row_catalogs['id']?>" <?php if(isset($_GET['id']) && isset($row_product['catalog_id']) && $row_catalogs['id']==$row_product['catalog_id']){ ?>selected<?php } ?>><?php echo $row_catalogs['name']?></option>
  <?php
} while ($row_catalogs = mysql_fetch_assoc($catalogs));
  $rows = mysql_num_rows($catalogs);
  if($rows > 0) {
      mysql_data_seek($catalogs, 0);
	  $row_catalogs = mysql_fetch_assoc($catalogs);
  }
?>
</select>