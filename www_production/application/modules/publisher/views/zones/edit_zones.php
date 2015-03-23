<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#editzoneform").validationEngine();
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
		var zoneid = document.getElementById('zoneid').value;
		
		var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("publisher/zones/username_check2"); ?>",
			data:"zonename="+zonename + "&zoneid="+ zoneid,
			success : function (data) {
				if(data == 'no')
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
		if(storeData == 'no')
		{
			return '<?php echo $this->lang->line('lang_zones_username_already_exists'); ?>';
		}
	}
   
	function checkPricingModel(){
		var pval = document.getElementById('prev_pricing_model').value;
		var cval = document.getElementById('pricing_model').value;
		
				if(cval != pval) {
						jConfirm("<?php echo $this->lang->line('lang_edit_zone_rem_links'); ?>","<?php echo $this->lang->line('lang_edit_zone_rem_links_title') ?>",function(r){
							if(r)
							{
								document.getElementById('editzoneform').submit();
							}else{
								return false;
							}
						});
				
				} else {
					document.getElementById('editzoneform').submit();
				}
	}

</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_editzone_fieldset');?></h1>
 <form id="editzoneform"  action="<?php echo site_url("publisher/zones/update_zones/".$zoneid);?>" method="post">
 <?php foreach($record as $row): ?>      
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line("label_inventory_editzone_fieldset");?> </legend>
                  			<?php echo validation_errors(); ?>

					<p>
						<label for="zonename"><?php echo $this->lang->line("label_inventory_zone_name");?>&nbsp;<span style="color:red;" >*</span></label><input type="text" name="zonename" id="zonename" class="validate[required,funcCall[usernameDupcheck],funcCall[uresData]]"  alt="<?php echo $this->lang->line('label_alert_zonesname'); ?>" value="<?php echo form_text((set_value('zonename') != '')?set_value('zonename'):$row->zonename);?>"/>
						
						<input type="hidden" name="zoneid" id="zoneid" value="<?php echo $row->zoneid;?>" />
						
						<input type="hidden" name="affiliateid" value="<?php echo $row->affiliateid;endforeach;?>" />
					</p>
					
					

		
		 <?php foreach($record as $row): ?>
		<p>
		<label for="email"><?php echo $this->lang->line('label_inventory_zone_type'); ?>&nbsp;<span style="color:red;" >*</span></label>
		<input type="radio" onchange="showBannerForm()" checked="checked" disabled="disabled" name="zone_type"  id="zone_type" <?php echo (form_text(((set_value('zone_type') != '')?set_value('zone_type'):$row->master_zone)==-2)?"checked":""); ?> value="1"/>&nbsp;&nbsp;
<?php echo $this->lang->line('label_zone_type1'); ?><br/>
<input type="radio" onchange="showBannerForm()" name="zone_type"  disabled="disabled" id="zone_type" <?php echo (form_text(((set_value('zone_type') != '')?set_value('zone_type'):$row->master_zone)==-1)?"checked":""); ?> value="2"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type2'); ?><br/>
		<label>&nbsp;</label>
		<input type="radio" onchange="showBannerForm()" name="zone_type"  disabled="disabled" id="zone_type" <?php echo (form_text(((set_value('zone_type') != '')?set_value('zone_type'):$row->master_zone)==-3)?"checked":""); ?> value="3"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_zone_type3'); ?>
		</p><?php endforeach;?>
		
		
		<p id="zone_size_sec">
		
		<?php foreach($record as $row):?>
		<label for="zone_size"><?php echo $this->lang->line('label_inventory_zone_sizes'); ?>&nbsp;<span style="color:red;" >*</span></label>
		<input type="text" value="<?php echo $row->width; ?>x<?php echo $row->height; ?>" disabled="disabled" />
		
		
		</p>
		<?php endforeach;?>

		 <p>
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?>&nbsp;<span style="color:red;" >*</span></label>
                
				
				<?php
				foreach ($model as $rowvalue)
				{ 
					$sel_pricing_model = (form_text(set_value('pricing_model') != '')?set_value('pricing_model'):$rowvalue->revenue_type);
				
				}
				foreach ($mlist as $object) 
				{
				$option[$object->revenue_type_value] =$object->revenue_type; 
					
				 } ?>
				<?php echo form_dropdown('pricing_model', $option, $sel_pricing_model, "class='validate[required] sf' name='pricing_model' id='pricing_model' onchange='revenue_type();'  alt='".$this->lang->line('label_alert_select_pricemodel')."'"); ?> 
            </p>

		<p id="rtbZone">
			<label for="rtb"><?php echo $this->lang->line('label_realtime_bidding'); ?></label>
        		<input type="checkbox" name="rtb"  id="rtb" <?php echo ($row->rtb==1)?'checked=checked':''; ?> value="1"/>&nbsp;&nbsp;<?php echo $this->lang->line('label_make_zone_realtime_bidding'); ?>
		</p>
                    
                    
                    <p>
                    	<button type="button" onclick="checkPricingModel()"><?php echo $this->lang->line("label_submit");?></button>
                    	<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    <input type="hidden" id="ucheckdata" name="ucheckdata" />
		    <input type="hidden" id="prev_pricing_model" name="prev_pricing_model" value="<?php echo $rowvalue->revenue_type; ?>" />
                </fieldset>
            </div><!--form-->
  	     </form>
<script>

	jQuery(document).ready(function(){

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
	});

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

