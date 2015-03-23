	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/colorpicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/elements.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css" type="text/css"/>

	<script type="text/javascript">
	
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_store").validationEngine();
	
		
		
	});
	
	
	</script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script>
        function check_lat() {
			var city=document.getElementById("city").value;
			var ad1=document.getElementById("ad1").value;
			var ad2=document.getElementById("ad2").value;
			var address=ad1+" "+ad2+" "+city ;
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
            'address': address
            }, function(results, status) {      
	
                var lat=document.getElementById("lat").value=results[0].geometry.location.lat();  
                var lng=document.getElementById("lon").value=results[0].geometry.location.lng();        
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
			<form id="add_store" name="add_store" action="<?php echo site_url('advertiser/storefront/add'); ?>" method="post" >
			<h1 class="pageTitle"><?php echo "Add Store" ?></h1>
			        <div class="form_default">
			        <fieldset>
			            <legend><?php echo "New Store Details"; ?></legend>
					
			            <p>
			                <label for="name"><?php echo "Store Name"; ?><span style="color:red;">*</span></label>
			                <input type="text" name="store_name"  id="store_name" class="validate[required] sf" alt="Please Enter Store Name" value="<?php echo $campaign->campaignname; ?>"/>
			                
			            </p>
			            
			               
						<p>
			                <label>Address1<span style="color:red;">*</span></label>
			                <input type="text" name="ad1"  id="ad1" class="validate[required] sf" alt="Please Enter Address1. "  />
			            </p>
			
			            <p>
			                <label>Address2<span style="color:red;">*</span></label>
			                <input type="text" name="ad2"  id="ad2" class="validate[required] sf" alt="Please Enter Address2"  />
			            </p>
			            
						<p>
			                <label><?php echo "City" ?><span style="color:red;">*</span></label>
			                <input type="text" name="city"  id="city" onblur="check_lat()" class="validate[required,custom[onlyLetterSp]]sf"  alt="Please Enter City."
							 value="<?php echo  form_text($campaign->revenue); ?>"/>
			            </p>
			
			            <p >
			                <label>State<span style="color:red;">*</span></label>
			                <input type="text" name="state"  id="state" class="validate[required,custom[onlyLetterSp]]sf"  alt="Please Enter State." />
			            </p>
			            
			            <p >
			                <label>Pin<span style="color:red;">*</span></label>
			                <input type="text" name="pin"  id="pin" class="validate[required,custom[integer]]sf"  alt="Please Enter Pin." />
			            </p>
			            
			            <p>
			                <label>Country<span style="color:red;">*</span></label>
			                <input type="text" name="country"  id="country" class="validate[required,custom[onlyLetterSp]]sf"  alt="Please Enter Country." />
			            </p>
			            
			            <p>
			                <label>Lat<span style="color:red;">*</span></label>
			                <input type="text" name="lat"  id="lat" class="validate[required] sf" alt="Please Enter Latitude"  />
			            </p>
			            
			            <p >
			                <label>Lon<span style="color:red;">*</span></label>
			                <input type="text" name="lon"  id="lon" class="validate[required] sf" alt="Please Enter Longitude"  />
			            </p>
			            
			            <p>
			                <label>Aisle<span style="color:red;">*</span></label>
			                <input type="text" name="aisle"  id="aisle" class="validate[required] sf" alt="Please Enter Aisle"  />
			            </p>
			            
			            <p>
			                <label>Shelf<span style="color:red;">*</span></label>
			                <input type="text" name="shelf"  id="shelf" class="validate[required] sf" alt="Please Enter Shelf"  />
			            </p>
			            <p >
			                <label>Floor<span style="color:red;">*</span></label>
			                <input type="text" name="floor"  id="floor" class="validate[required] sf" alt="Please Enter Floor"  />
			            </p>
			            
			            <p >
			                <label>Location 01<span style="color:red;">*</span></label>
			                <input type="text" name="loc_01"  id="loc_01" class="validate[required] sf" alt="Please Enter Location 01"  />
			            </p>
			            <p >
			                <label >Location 02<span style="color:red;">*</span></label>
			                <input type="text" name="loc_02"  id="loc_02" class="validate[required] sf" alt="Please Enter Location 02"  />
			            </p>
			            <p >
			                <label >Location 03<span style="color:red;">*</span></label>
			                <input type="text" name="loc_03"  id="loc_03" class="validate[required] sf" alt="Please Enter Location 03"  />
			            </p>
			            
			            <p>
			                <button ><?php echo $this->lang->line('label_submit'); ?></button>
			                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
			                <!--<button type="button" style="margin-left:10px;" onclick="javascript: check_lat();" ><?php echo "Check" ?></button> -->
			            </p>
						
			        </fieldset>
			    </div><!--form-->
			    </form>
			    
			
			
		
	
