<?php require_once($_SERVER['DOCUMENT_ROOT'].'/Connections/localhost.php'); ?>
<?php

$query_userlevels = "SELECT id, name FROM user_levels";
$userlevels = mysqli_query($localhost)or die(mysqli_error($localhost),$query_userlevels);
$row_userlevels = mysqli_fetch_assoc($userlevels);
$totalRows_userlevels = mysqli_num_rows($userlevels);
?> 
<?php do { ?>
<label>
<input name="user_group_value[]" <?php if(isset($row_promotion['user_group']) && in_array($row_userlevels['id'],explode(",",$row_promotion['user_group_value']))){?>checked<?php } ?> type="checkbox" id="user_group_value" value="<?php echo $row_userlevels['id']; ?>" />
</label>
<?php echo $row_userlevels['name']; ?>
<?php } while ($row_userlevels = mysqli_fetch_assoc($userlevels)); ?>