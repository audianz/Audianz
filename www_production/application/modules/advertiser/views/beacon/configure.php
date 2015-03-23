<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/colorpicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/elements.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-timepicker-addon.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css" type="text/css"/>

	<script type="text/javascript">
	
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#configure_beacon").validationEngine();
		//document.getElementById('uuid').disabled=true;;
	});
	
	
	function Numericcheck(field, rules, i, options)
	{
		var reg 		= /^[-]?[0-9\.]+$/;
		var value	=		field.val();
		if(!reg.test(value))
			{
				return "<?php echo $this->lang->line('lang_inventory_websites_add_inor-idonly_numbers'); ?>";
			}
		
	}
	
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
	
   </script>
		<script>
		function goToList()
		{
			window.location.href="<?php echo base_url();?>index.php/advertiser/beacon";
		}
		</script>
		<form id="configure_beacon" name="configure_beacon" action="<?php echo site_url('advertiser/beacon/save_configurations/'.$beacon_info[0]->Beacon_Seq_ID); ?>" method="post" >
		<h1 class="pageTitle"><?php echo "Configure Beacon" ?></h1>
		        <div class="configure_beacon" >
		        <fieldset>
		            <legend><?php echo "Enter Beacon details"; ?></legend>
		            <p>
		            <p><label ><?php echo "UUID"; ?></label>
		             <input type="text" class="validate[required] sf" style="background-color:#E0E0E0;margin-left:7% "  name="uuid"  id="uuid" value="<?php echo $beacon_info[0]->Beacon_UUID; ?>"  readonly />
		             <label ><?php echo "Customer Id"; ?></label>
		             <input type="text" name="cus_id"  id="cus_id" style="background-color:#E0E0E0 ;width:20%;" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_campname'); ?>" value="<?php echo $beacon_info[0]->Customer_ID; ?>" readonly />
		             <label ><?php echo "Agency Id";?></label>
		             <input type="text" name="agency_id"  id="agency_id" style="background-color:#E0E0E0 ;width:20%;"  class="validate[required] sf"  value="<?php echo $beacon_info[0]->Agency_ID; ?>" readonly />
		             </p> 
		            <p>  
					 <label ><?php echo "Beacon Name"; ?><span style="color:red;">*</span></label>
		             <input type="text" name="beacon_name" style="margin-left:20px;"  id="beacon_name" class="validate[required] sf" alt="Please Enter Beacon Name"  value="<?php echo $beacon_info[0]->Beacon_name ?>"/>
		            </p>
		            <p>
		            <label><?php echo "Major id" ?></label>
		            <input type="text" name="major_id"  id="major_id" style="background-color:#E0E0E0;margin-left:55px; " class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" 
						value="<?php echo $beacon_info[0]->Beacon_Major_ID; ?>" readonly />
						
					<label><?php echo "Minor Id" ?><span style="color:red;">*</span></label>
		            <input type="text" name="minor_id"  id="minor_id" class="validate[required,min[1],max[9999],custom[integer]] sf"  alt="<?php echo "Please Enter Minor Id between 1 to 9999" ?>"  value="<?php echo $beacon_info[0]->Beacon_Minor_ID; ?>" />
		            
		            </p>
		            <p>
		                <label><?php echo " Description"; ?><span style="color:red;">*</span></label>
		                <textarea rows="2" cols="33" style="margin-left:3%" name="description" id="description"  class="validate[required] sf" alt="Please enter some description"  value="Enter short beacon description here.." >  </textarea>
		            </p>
		
		           
		
		            <p>
					<label>Beacon Location<span style="color:red;" >*</span></label>
						<select name="beacon_location" id="beacon_location" style="margin-left:4px;" class="validate[required] " alt="<?php echo "Please Select Beacon location";//$this->lang->line("label_alert_pricing_model"); ?>" onchange="revenue_type();">
							<option value="" <?php echo set_select('beacon_location', '', TRUE); ?> >Choose Beacon Location</option>
							<?php for($i=0;$i<count($beacon_locations);$i++) { ?>
							<option value="<?php echo $beacon_locations[$i]->id; ?> " ><?php echo $beacon_locations[$i]->poi_name .",". $beacon_locations[$i]->pin ; ?></option>
							<?php } ?>
						</select>
					</p>
					
					<p><label style="width:130px; "><?php echo "Physical Tag"?></span></label>
		                <input type="text" name="tag" style="margin-left:3%;background-color:#E0E0E0;" id="tag" class="validate[required] sf"   value="<?php echo $beacon_info[0]->Physical_tag ; ?>" readonly />
		            </p>
					
					<p>
		                <button ><?php echo $this->lang->line('label_submit'); ?></button>
		                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
		            </p>
					
