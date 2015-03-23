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

			url: "<?php echo site_url("admin/inventory_zones/username_check"); ?>",
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
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?></h1>
 <form id="zoneform" action="<?php echo site_url("admin/inventory_zones/insert/".$affiliateid.""); ?>" method="post">
        
<div class="form_default">
<fieldset>
<legend><?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?> </legend>

		<?php echo validation_errors(); ?>

		<p>
		<label for="name"><?php echo $this->lang->line('label_inventory_zone_name'); ?></label>
		<input type="text" name="zonename"  id="name" class="validate[required,funcCall[usernameDupcheck],funcCall[uresData]]"  alt="<?php echo $this->lang->line('label_alert_zonesname'); ?>" value="<?php echo form_text(set_value('zonename')); ?>"/>
		</p>

		<p> 
                <label for="name"><?php echo $this->lang->line('label_inventory_zone_website'); ?></label>
                
				
				<?php
				
				if($affiliate == 0)
				{
					$sel_website = form_text((set_value('website')));
				}
				else
				{
					foreach ($affiliate as $objval)
					{
						
						$sel_website = form_text((set_value('website') != '')?set_value('website'):$objval->affiliateid);
					}
				}
				
				$options[""] =$this->lang->line('label_select_website');
				/*foreach ($aff as $cobj) { $options[$cobj->affiliateid] =$cobj->contact; } ?>
				<?php echo form_dropdown('website', $options,$sel_website,"class='validate[required] sf' alt='".$this->lang->line('label_alert_select_website')."'"); ?>*/?>
				<select alt="Select website name" class="validate[required] sf" name="website" id="form-validation-field-0"
				 <?php if (isset($sel_website) AND(!empty($sel_website))): ?> disabled="disabled" <?php endif;?>>
					<option selected="selected" value="">Select website</option>
					<?php foreach ($aff as $cobj) :?>
					<option value="<?php echo $cobj->affiliateid?>" <?php if ($sel_website==$cobj->affiliateid) : ?> selected="selected" <?php endif;?>><?php echo $cobj->contact?></option>
					<?php endforeach;?>	
				</select>
				<?php if (isset($sel_website) AND(!empty($sel_website))):
						$aff_id=$this->uri->segment('4')?> 
					<input type="hidden" name="website" id="form-validation-field-0" value="<?php echo $aff_id?>"/>
				<?php endif;?>
</p>
<p>
<label for="email"><?php echo $this->lang->line('label_inventory_zone_type');?></label>
<input type="radio" onchange="showBannerForm()" checked="checked" name="zone_type" id="zone_type" value="1"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type1').'('.$mob_screens[0]['width'].'X'.$mob_screens[0]['height'].','.' '.$mob_screens[1]['width'].'X'.$mob_screens[1]['height'].','.' '.$mob_screens[2]['width'].'X'.$mob_screens[2]['height'].','.' '.$mob_screens[3]['width'].'X'.$mob_screens[3]['height'].')'; ?><br/>
<input type="radio" onchange="showBannerForm()" name="zone_type" id="zone_type" value="2"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type2');?><br/>
<label>&nbsp;</label>
<input type="radio" onchange="showBannerForm()" name="zone_type" id="zone_type" value="3"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type3');?>
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
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?></label>
                <select name="pricing_model" id="pricing_model" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_select_pricemodel'); ?>" onchange="revenue_type();" >
                    <option value="" <?php echo form_text(set_select('website', '', TRUE)); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_pricing_model'); ?></option>
                    <?php if(count($price_model)>0){
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
