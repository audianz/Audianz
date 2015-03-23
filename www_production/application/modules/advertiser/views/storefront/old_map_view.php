
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
var ar = JSON.parse( '<?php echo json_encode($storefront_list); ?>' );
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
		
		var contentString = '<div id="content" width="100px">'+
      '<div style="margin-left:5%;height:5%;text-align:center">'+
      '<h3 id="firstHeading" class="firstHeading">'+ar[i]['poi_name']+'</h3>'+
      '</div>'+
      '<div style="text-align:center"> <p>'+ar[i]['pin']+'</p>'+
      '<p>'+ar[i]['address1']+','+ar[i]['city']+'</p>'+
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
<div style="z-index:10; " class="map_form" >
	<form id ="map_search" name="map_search" action="<?php echo site_url('advertiser/storefront/map_search') ?>"  method="post" > 
		<p><input type="text" id="location" name="location" placeholder="Search your location here ...." /> <img src="<?php echo base_url(); ?>assets/images/icons/small/black/search.png"  /> 
		<img src="<?php echo base_url(); ?>assets/images/reload_icon.png" onclick="refresh_map()" /></p>
		
		</form>
</div>

<div id="googleMap" style="width:100%;height:400px"></div>
