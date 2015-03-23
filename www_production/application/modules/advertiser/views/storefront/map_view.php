
<!DOCTYPE html>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
	function refresh_map()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/storefront/map_view"; 
	}
	function click_act()
	{ 
				var store =document.getElementById('location').value;
				if(store!='')
				{
				
					jConfirm('<?php echo "Are you sure You want to search the stores"; ?>','<?php echo "Search Store"; ?>',function(r)
					{
						if(r)
						{
							
							jQuery.get('<?php echo site_url('advertiser/storefront/map_store'); ?>', {'arr[]': store}, function(response) {
							//location.reload();
							var ar=response;
							//alert(ar);
	                });
	            }
	           
					});
			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Locations"; ?>');
			} 
		
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
	zoom:10,
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
      '<div style="width:70%;height:5%;text-align:center">'+
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

	function add_store()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/storefront/add_store";
	}
	
	function goToList()
	{
		window.location.href="<?php echo base_url();?>index.php/advertiser/storefront/show_list";
	}
	
	function delete_stores()
	{
		jConfirm('<?php echo "You can delete stores from list."; ?>','<?php echo "Delete Stores"; ?>',function(r)
					{
						if(r)
						goToList();
						
					} );
	}
					
		
</script>
<div style="z-index:100; " class="map_form" >
	
	<form id ="map_search" name="map_search" onsubmit="click_act()"   > 
		<p>
			
				<input type="text" id="location"  name="location"  placeholder="Search your location here ...." /> 
				
			<img src="<?php echo base_url(); ?>assets/images/icons/small/black/search.png"  /> 
			<img src="<?php echo base_url(); ?>assets/images/reload_icon.png" onclick="refresh_map()" />
		
			<a href="javascript:void(0);" onclick="add_store();" class="iconlink" style="margin-left:35%" >
			<span><?php echo "Add Store"; ?></span> 
			</a> 
			<a href="javascript:void(0);" onclick="goToList();" class="iconlink" style="margin-left:2%" >
			<span><?php echo "List View"; ?></span> 
			</a>
			
			<a href="javascript:void(0);" onclick="delete_stores();" class="iconlink" style="margin-left:2%" >
			<span><?php echo "Delete Stores"; ?></span> 
			</a>
			
				
		</p>
		</form>
	
	
<!--	<input type="button" onclick="click_act();" value="check" /> -->
</div>

<div id="googleMap" style="width:100%;height:400px"></div>
