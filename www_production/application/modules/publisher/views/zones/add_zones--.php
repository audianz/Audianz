<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#zoneform").validationEngine();
	});
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/

	function usernameDupcheck(field, rules, i, options)
	{
		var zonename = field.val();
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("publisher/zones/username_check"); ?>",
			data: "zonename="+zonename,

			success : function (data) {
				if(data == 'yes')
				{
					document.getElementById('ucheckdata').value = data;
				}
				else
				{
					document.getElementById('ucheckdata').value = data;
				}
			}
		});
				
	}
	
	function uresData(field, rules, i, options)
	{
		var storeData = document.getElementById('ucheckdata').value;
		if(storeData == 'yes')
		{
			return '<?php echo $this->lang->line('lang_zones_username_already_exists'); ?>';
		}
	}
   

</script>
<h1 class="pageTitle"><?php echo $this->lang->line("label_inventory_addzones_fieldset");?></h1>
 <form id="zoneform" action="<?php echo site_url("publisher/zones/insert");?>" method="post">
        
<div class="form_default">
<fieldset>
<legend><?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?> </legend>

		<?php echo validation_errors(); ?>

		<p>
			<label for="name"><?php echo $this->lang->line('label_inventory_zone_name'); ?> <span style="color:red;" >*</span></label>
			<input type="text" name="zonename"  id="name" class="validate[required,funcCall[usernameDupcheck],funcCall[uresData]]"  alt="<?php echo $this->lang->line('label_alert_zonesname'); ?>" value="<?php echo form_text(set_value('zonename')); ?>"/>
		</p>
		
		<p>
			<label for="email"><?php echo $this->lang->line('label_inventory_zone_type');?> <span style="color:red;" >*</span></label>
			<input type="radio" onchange="showBannerForm()" checked="checked" name="zone_type" id="zone_type" value="1"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type_1');?><br/>
			<input type="radio" onchange="showBannerForm()" name="zone_type" id="zone_type" value="2"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type_2');?><br/>
			<label>&nbsp;</label>
			<input type="radio" onchange="showBannerForm()" name="zone_type" id="zone_type" value="3"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type_3');?>
		</p>

		<p id="zone_size_sec">
		<label for="zone_size"><?php echo $this->lang->line('label_inventory_zone_sizes'); ?></label>
		<select name="zone_size" id="zone_size" class="validate[required]"  alt="<?php echo $this->lang->line('label_alert_select_zonesize'); ?>">
		 <option value="" <?php echo form_text(set_select('zone_size', '', TRUE)); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_zone_sizes'); ?></option>
		 
		 
		<option value="1"><?php echo $this->lang->line('label_zone_size1'); ?></option>
		<option value="2"?><?php echo $this->lang->line('label_zone_size2'); ?></option>
		<option value="3"?><?php echo $this->lang->line('label_zone_size3'); ?></option>
		</select>
		</p>


		
		 <p>
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?> <span style="color:red;" >*</span></label>
                <select name="pricing_model" id="pricing_model" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_select_pricemodel'); ?>" onchange="revenue_type();" >
                    <option value="" <?php echo form_text(set_select('website', '', TRUE)); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_pricing_model'); ?></option>
                    <?php
                    if(count($price_model)>0)
                    {
                            for($i=0;$i<count($price_model);$i++)
                            {
                            ?>
                            <option value="<?php echo $price_model[$i]->revenue_type_value; ?>" <?php echo form_text(set_select('advertiser', $price_model[$i]->revenue_type)); ?>><?php echo $price_model[$i]->revenue_type; ?></option>
                    <?php 
                            }
                    } ?>
                </select>
            </p>

		<p id="rtbZone">
			<label for="rtb"><?php echo $this->lang->line('label_realtime_bidding'); ?></label>
        		<input type="checkbox" name="rtb"  id="rtb" value="1"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_make_zone_realtime_bidding'); ?>
		</p>

		<input type="hidden" id="ucheckdata" name="ucheckdata" />

		<p>
		<button><?php echo $this->lang->line("label_submit"); ?></button>
		<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
		</p>

</fieldset>
</div><!--form-->
</form>

<script>

	jQuery('#rtbZone').hide();
	/* Display Revenue Type Fields */
     	function revenue_type()
     	{
		var revenue = document.getElementById('pricing_model').value;
		//alert(revenue);
		if(revenue!='')
		{
		    if(revenue==1 || revenue==2)
		    {                
		        jQuery('#rtbZone').show();                
		    }           
		    else if(revenue==3)
		    {              
		        jQuery('#rtbZone').hide();               
		    }
		}
		else
		{
		    jQuery('#rtbZone').hide();  
		}
     	}     
     
 	function goToList()
	{
		document.location.href='<?php echo site_url("publisher/zones"); ?>';
	}
 	showBannerForm();
 	function showBannerForm(){
	
			if(jQuery('input[name=zone_type]:checked').val()=="3")
			{
				jQuery('#zone_size_sec').show();
			}
			else{
				jQuery('#zone_size_sec').hide();
			}
	}
</script>		   

