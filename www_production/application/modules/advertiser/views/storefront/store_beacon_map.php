
<!DOCTYPE html>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
	function refresh_map()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/storefront/map_view"; 
	}
	
</script>

<script>
var myCenter=new google.maps.LatLng(28.6100,77.2300);
var ar = JSON.parse( '<?php echo json_encode($stores); ?>' );
for(i=0;i<ar.length;i++)
{
	if(ar[i]['Beacon_Status']==1)
	{
		ar[i]['status']='Active';
	}
	else
	{
		ar[i]['status']='Passive';
	}
}	
	
</script>
<script>
function initialize()
{
	var mapProp = {
	center:myCenter,
	zoom:4,
	mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	
	
	var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
	var infowindow = new google.maps.InfoWindow();

	var marker, i;
	for (i = 0; i < ar.length; i++) 
	{ 
		marker = new google.maps.Marker({
		position: new google.maps.LatLng(ar[i]['lat'], ar[i]['lon']),
		map: map
     });

  google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
		var content_string="<div id='outer' style='width:200px'><div id='popupbox'> <div id='weathericon'><a href='<?php echo base_url();?>index.php/advertiser/storefront?id=<?php echo $siteinfo_array[$i]['site_id']; ?>'> <img src='<?php echo base_url(); ?>assets/images/icons/editor.png'></a></div><div id='siteInfo'><?php echo '<h3>'.$siteinfo_array[$i]['customer'].'</h3> <p>'.$siteinfo_array[$i]['customer_city'].' local time : '.$siteinfo_array[$i]['local_time'].'</p> <p> lastupdate:'.$siteinfo_array[$i]['last_update']. '</p></div>'; ?><div id='infoButton'><a href='<?php echo base_url();?>index.php/sitemonitor/showSiteStatus?site_id=<?php echo $siteinfo_array[$i]['site_id']; ?>&is_default=2'><img src='<?php echo base_url(); ?>assets/images/info-i.png' width='30px' height='30px'></a></div></div><div style='float:left;margin-left:20%' ><p><?php echo $siteinfo_array[$i]['comment']; ?> </p></div></div>";
		
		var contentString = '<div id="content" width="100px">'+
      '<div style="margin-left:5%;height:7%;text-align:center" >'+
      '<h3 id="firstHeading" class="firstHeading">'+ar[i]['poi_name']+'</h3>'+
      '</div>'+
      '<div style="text-align:center"> <p>'+ar[i]['pin']+'</p>'+
      '<p>'+ar[i]['address1']+','+ar[i]['city']+'</p>'+
      '<p><b>Beacon Name:</b>'+ar[i]['Beacon_name']+'</p>'+
      '<p><b>Beacon Status:</b>'+ar[i]['status']+'</p>'+
      '<p></p>'+
      '</div>'+
      
      '</div>';

		
      infowindow.setContent(contentString);
      infowindow.open(map, marker);
    }
  })(marker, i));
}
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div style="z-index:100; " class="map_form" >
	<form id ="map_search" name="map_search" onsubmit="click_act()"   > 
		<p><input type="text" id="location"  name="location"  placeholder="Search your location here ...." /> <img src="<?php echo base_url(); ?>assets/images/icons/small/black/search.png"  /> 
		<img src="<?php echo base_url(); ?>assets/images/reload_icon.png" onclick="refresh_map()" /></p>
		
	</form>
</div>

<div id="googleMap" style="width:100%;height:400px"></div>
