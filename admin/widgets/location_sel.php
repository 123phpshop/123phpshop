<?php //require_once('../../Connections/localhost.php'); ?><?php //require_once('../../Connections/localhost.php'); ?>
<?php
mysql_select_db($database_localhost, $localhost);
$query_areas = "SELECT id, name FROM area WHERE pid = 0";
$areas = mysql_query($query_areas, $localhost) or die(mysql_error());
$row_areas = mysql_fetch_assoc($areas);
$totalRows_areas = mysql_num_rows($areas);


mysql_select_db($database_localhost, $localhost);
$query_areas_for_city = "SELECT id, name FROM area WHERE pid = 0";
$areas_for_city = mysql_query($query_areas_for_city, $localhost) or die(mysql_error());
  
 
mysql_select_db($database_localhost, $localhost);
$query_areas_for_district = "SELECT id, name FROM area WHERE pid = 0";
$areas_for_district = mysql_query($query_areas_for_district, $localhost) or die(mysql_error());

?> 
<script src="/js/jquery-1.7.2.min.js"></script>
<script src="/js/jsAddress.js"></script>
<script>
function show_city(province_name){
   $(".city_list_item[province_name="+province_name+"]").show();
   $(".city_list_item[province_name!="+province_name+"]").hide();
   $(".district_list_item").hide();
}

function show_disticts(city_name){
   $(".district_list_item[city_name="+city_name+"]").show();
   $(".district_list_item[city_name!="+city_name+"]").hide();
}
</script>
  <table width="960" border="0">
  <tr>
    <td width="131" valign="top"><table width="118" border="0" id="province_box">
  <?php do { ?>
    <tr>
      <td width="108" > 
        <input type="checkbox" name="area[]" class="province" province_name="<?php echo $row_areas['name']; ?>" id="province_<?php echo $row_areas['id']; ?>" value="<?php echo $row_areas['name']; ?>">
       <label onMouseOver="show_city('<?php echo $row_areas['name']; ?>')"><?php echo $row_areas['name']; ?></label></td>
    </tr>
    <?php } while ($row_areas = mysql_fetch_assoc($areas)); ?>
</table></td>
    <td width="201" valign="top" id="city_box">
	<?php while($row_areas_for_city = mysql_fetch_assoc($areas_for_city)){ ?>
	<div class="city_list_item" province_name="<?php echo $row_areas_for_city['name']; ?>" id="city_<?php echo $row_areas_for_city['id'];?>" style="display:none;">
 		<?php 
			mysql_select_db($database_localhost, $localhost);
			$query_cities = "SELECT * FROM area WHERE pid = ".$row_areas_for_city['id'];
			$cities = mysql_query($query_cities, $localhost) or die(mysql_error());
			$totalRows_cities = mysql_num_rows($cities);
			while($row_cities = mysql_fetch_assoc($cities)){
		?>
       	<input type="checkbox" name="checkbox" value="checkbox"><span onMouseOver="show_disticts('<?php echo $row_cities['name']; ?>')"><?php echo $row_cities['name']; ?></span></br>
 	 	<?php } ?>
	  </div>
	<?php } ?>	</td><td width="606" valign="top">
		<?php 
		// 获取各个省份的信息
		while($row_areas_for_district = mysql_fetch_assoc($areas_for_district)){
//			获取这个省份下面的城市的信息
			mysql_select_db($database_localhost, $localhost);
			$query_cities = "SELECT * FROM area WHERE pid = ".$row_areas_for_district['id'];
			$cities = mysql_query($query_cities, $localhost) or die(mysql_error());
			$totalRows_cities = mysql_num_rows($cities);
			if($totalRows_cities>0){
			while($row_cities = mysql_fetch_assoc($cities)){
				//	 获取这个城市下面的区县的信息
				mysql_select_db($database_localhost, $localhost);
				$query_distict = "SELECT * FROM area WHERE pid = ".$row_cities['id'];
				$disticties = mysql_query($query_distict, $localhost) or die(mysql_error());
				$totalRows_distict = mysql_num_rows($disticties);?>
				<div class="district_list_item" city_name="<?php echo $row_cities['name'];?>" style="display:none;" >
 				<?php 	while($row_distict = mysql_fetch_assoc($disticties)){?>
						<input type="checkbox" value=""><?php echo $row_distict['name'];?></br>
 				<?php 	} ?>
 				</div>
				<?php 	}
			}
		}
		?>   </td> 
   </tr>
</table>
