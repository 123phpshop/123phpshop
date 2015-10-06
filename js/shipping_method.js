function by_quantity(){
	  

	$(".by_quantity").show();
	$(".by_weight").hide();
}
function by_weight(){
	 
	
	$(".by_quantity").hide();
	$(".by_weight").show();
}

function select_province(province_name){
	
 //		这里检查是否勾选了省份，如果勾选了省份，那么将这个省份下面的城市全部勾选
	if($(".province[province_name="+province_name+"]").is(':checked')){
 		$(".city[province_name="+province_name+"]").attr("checked",'true');
 		return;
	}
	
  	$(".city[province_name="+province_name+"]").removeAttr("checked");
}


function select_city(city_name){
	if($(".city[city_name="+city_name+"]").is(':checked')){
 		$(".district[city_name="+city_name+"]").attr("checked",'true');
 		return;
	}
	
  	$(".district[city_name="+city_name+"]").removeAttr("checked");
}

function select_district(city_name){
	
}

 