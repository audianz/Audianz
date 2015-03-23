
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/colorpicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/elements.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css" type="text/css"/>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
    <script>
        function check_lat() {
			var city=document.getElementById("city").value;
			var ad=document.getElementById("ad1").value;
			var address=ad+" "+city ;
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
	

	<script type="text/javascript">
	
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_edit_store").validationEngine();
	
		
		
	});
	
	function Decimalcheck(field, rules, i, options)
	{
		var number = field.val();
		 var decimal= /^[0-9]+\.[0-9]{2}$/;
		 var str=number.split('.');
			if(typeof(str[1]) != "undefined" && str[1] !== null)
			{
				var len=str[1];
				var len_1=len.length;
				if(len_1 > 2)
				{	
					if ((!decimal.test(number)))
					{
						return '<?php echo "Cannot have more than 2 decimals." ?>'; 
					}
				}
			}
	}
	
	function mobileValidateNew(field, rules, i, options){


			var y = field.val();
                        
                        var numbers = /^[0-9]+$/;
            if(!(field.val().match(numbers)))  
          {  
          return '<?php echo $this->lang->line('label_myaccount_err_numbers_only'); ?>';  
           }   

                            if (y.length<10 || y.length>15)
           {
               
                 return '<?php echo $this->lang->line('label_myaccount_err_mobile_numbers'); ?>';
           }


	 }
	 
	</script>
	<script>
		function goToList()
		{
			window.location.href="<?php echo base_url();?>index.php/advertiser/storefront";
		}
	</script>

		<form id="form_edit_store" name="form_edit_store" action="<?php echo site_url('advertiser/storefront/edit_process/'.$store_info[0]['id']); ?>" method="post" >
		<h1 class="pageTitle"><?php echo "Edit Store Information"; ?></h1>
		        <div class="form_default">
		        <fieldset>
		            <legend><?php echo "Edit Store Details";?></legend>
				
		            <p>
		                <label><?php echo "Store Name"; ?><span style="color:red;">*</span></label>
		                <input type="text" name="store_name"  id="store_name" class="validate[required] sf" alt="Please Enter Store Name"  value="<?php echo $store_info[0]['poi_name']; ?>"/>
		                
		            </p>
		            
		            <p>
		                <label><?php echo "Address1"; ?><span style="color:red;">*</span></label>
		                <input type="text" name="ad1"  id="ad1" class="validate[required] sf" alt="Please Enter Address1." 
						value="<?php  echo form_text($store_info[0]['address1'] ); ?>"/>
		            </p>
		
		            <p>
		                <label><?php echo "Address2" ?><span style="color:red;">*</span></label>
		                <input type="text" name="ad2"  id="ad2" class="validate[required] sf" alt="Please Enter Address2." 
						value="<?php echo  form_text($store_info[0]['address2'] ); ?>"/>
		            </p>
					
		            <p>
		                <label><?php echo "City" ?><span style="color:red;">*</span></label>
		                <input type="text" name="city"  id="city" class="validate[required] sf" onblur="check_lat()" alt="Please Enter City."
						 value="<?php echo  form_text($store_info[0]['city']); ?>"/>
		            </p>
		
		            <p>
		                <label>State<span style="color:red;">*</span></label>
		                <input type="text" name="state"  id="state" class="validate[required] sf" alt="Please Enter State." 
						value="<?php echo  form_text($store_info[0]['state']); ?>"/>
		            </p>
		            
		            <p >
		                <label>Pin<span style="color:red;">*</span></label>
		                <input type="text" name="pin"  id="pin" class="validate[required,custom[integer]] sf" alt="Please Enter Pin."  
						value="<?php echo  form_text($store_info[0]['pin']); ?>"/>
		            </p>
		            
		            <p>
		                <label>Country<span style="color:red;">*</span></label>
		                <input type="text" name="country"  id="country" class="validate[required,custom[onlyLetterSp]]sf"  alt="Please Enter Country."
						value="<?php echo  form_text($store_info[0]['country']); ?>"/>
		            </p>
		            <p>
		                <label>Contact No.<span style="color:red;">*</span></label>
		                <input type="text" name="tel"  id="tel" class="validate[required,funcCall[mobileValidateNew]] sf" alt="Please Enter Valid No." 
						value="<?php echo  form_text($store_info[0]['tel']); ?>"/>
		            </p>
		            
		            <p>
		                <label>Lat<span style="color:red;">*</span></label>
		                <input type="text" name="lat"  id="lat" class="validate[required] sf" alt="Please Enter Latitude" 
						value="<?php echo  form_text($store_info[0]['lat']); ?>"/>
		            </p>
		            
		            <p >
		                <label>Lon<span style="color:red;">*</span></label>
		                <input type="text" name="lon"  id="lon" class="validate[required] sf" alt="Please Enter Longitude" 
						value="<?php echo  form_text($store_info[0]['lon']); ?>"/>
		            </p>
		            
		            <p>
		                <label>Aisle<span style="color:red;">*</span></label>
		                <input type="text" name="aisle"  id="aisle" class="validate[required] sf"  alt="Please Enter Aisle." 
						value="<?php echo  form_text($store_info[0]['aisle']); ?>"/>
		            </p>
		            
		            <p>
		                <label>Shelf<span style="color:red;">*</span></label>
		                <input type="text" name="shelf"  id="shelf" class="validate[required] sf"  alt="Please Enter Shelf."
						value="<?php echo  form_text($store_info[0]['shelf']); ?>"/>
		            </p>
		            <p >
		                <label>Floor<span style="color:red;">*</span></label>
		                <input type="text" name="floor"  id="floor" class="validate[required] sf"  alt="Please Enter Floor."
						value="<?php echo  form_text($store_info[0]['floor']); ?>"/>
		            </p>
		            
		            <p>
		                <label>Location 01<span style="color:red;">*</span></label>
		                <input type="text" name="loc1"  id="loc1" class="validate[required] sf"  alt="Please Enter Locatio 01 "
						value="<?php echo  form_text($store_info[0]['location_01']); ?>"/>
		            </p>
		            
		            <p >
		                <label>Location 02<span style="color:red;">*</span></label>
		                <input type="text" name="loc2"  id="loc2" class="validate[required] sf"  alt="Please Enter Location 02"
						value="<?php echo  form_text($store_info[0]['location_02']); ?>"/>
		            </p>
		            <p >
		                <label>Location 03<span style="color:red;">*</span></label>
		                <input type="text"  name="loc3"  id="loc3" class="validate[required] sf"  alt="Please Enter Location 03"
						value="<?php echo  form_text($store_info[0]['location_03']); ?>"/>
		            </p>
		            
		            <p>
		                <button ><?php echo $this->lang->line('label_submit'); ?></button>
		                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
		            </p>
		
		        </fieldset>
		    </div>
		    </form>
		
		
	

