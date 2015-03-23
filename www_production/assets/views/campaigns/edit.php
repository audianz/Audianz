<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css" type="text/css"/>
<script src="<?php echo base_url(); ?>assets/form_validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url(); ?>assets/form_validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.alerts.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_edit_campaign").validationEngine();
                var revenue = document.getElementById('revcheck').value;
                
                if(revenue==1)
                {
                    jQuery('#rateimp').show();
                    jQuery('#rateclick').hide();
                    jQuery('#rateconv').hide();
                }
                else if(revenue==2)
                {
                    jQuery('#rateimp').hide();
                    jQuery('#rateclick').show();
                    jQuery('#rateconv').hide();
                }
                else if(revenue==3)
                {
                    jQuery('#rateimp').hide();
                    jQuery('#rateclick').hide();
                    jQuery('#rateconv').show();
                }
	});
    
		
    function goToList()
    {
        document.location.href='<?php echo site_url('advertiser/campaigns'); ?>';
    }
    
    function confirm_update()
    {
    	var ex_price = document.getElementById('ex_camp_rev_type').value;
    	var price = document.getElementById('pricing_model').value;
		var camp_link_count = document.getElementById('camp_link_count').value;
		if(ex_price!=price)
    	{
			if(camp_link_count>0)
			{
			jConfirm('<?php echo $this->lang->line('Edit_Camp_Alert'); ?>','<?php echo $this->lang->line('Edit_Camp_Alert_Title'); ?>',function(r){
                    if(r)
                    {
						document.getElementById("form_edit_campaign").submit();
						return true;
                    }
                    else
                    {
						return false;
                    }
            });
			return false;
			}
			else{
				return true;
			}
				
		}
		else
		{
			
			document.getElementById("form_edit_campaign").submit();
			return true;
		}
		
    }
	function Numericcheck(field, rules, i, options)
	{
			var reg 		= /^[-]?[0-9\.]+$/;
			var value	=		field.val();
			if(!reg.test(value))
				{
					return "<?php echo $this->lang->line('lang_inventory_websites_add_only_numbers'); ?>";
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
	function rateperclickcheck(field, rules, i, options)
	{
	   var value		=field.val();
	   var dailybudget	=jQuery('#budget').val();
	   if(value !='')
	   { 
		   if(parseFloat(dailybudget) < parseFloat(value))
		   {
			 return "Rate per Click should less than Daily Budget";
		   }
		}
	}
	
	function rateperimpcheck(field, rules, i, options)
	{
		
	   var value		=field.val();
	   var dailybudget	=jQuery('#budget').val();

	   if(value !='')
	   { 
		   if(parseFloat(dailybudget) < parseFloat(value))
		   {
			 return "Rate per Impression should less than Daily Budget";
		   }
		}
	}
	
	function rateperconcheck(field, rules, i, options)
	{
	   var value		=field.val();
	   var dailybudget	=jQuery('#budget').val();
	   if(value !='')
	   { 
		   if(parseFloat(dailybudget) < parseFloat(value))
		   {
			 return "Rate per Conversion should less than Daily Budget";
		   }
		}
	}
   
		
</script>
<?php
    if(count($campinfo)>0)
    {
        //print_r($campinfo);
        $campaign = $campinfo[0];
        if($campaign_id==0 || $campaign_id==''){
            $campaignidpro = $campaign->campaignid;
        }
        else{
            $campaignidpro = $campaign_id;
        }    
?>
<form id="form_edit_campaign" name="form_edit_campaign" action="<?php echo site_url('advertiser/campaigns/edit_process/'.$campaignidpro); ?>" method="post" >
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_edit_campaign'); ?></h1>
        <div class="form_default">
        <fieldset>
            <legend><?php echo $this->lang->line('label_inventory_edit_campaign'); ?></legend>
		<?php
			echo validation_errors();
			if($this->session->userdata('camp_error') != ""): ?>
				<div class="notification msgerror"><a class="close"></a><?php echo $this->session->userdata('camp_error'); ?></div>
                <?php
				$this->session->unset_userdata('camp_error');
            endif;
			
			if($this->session->userdata('camp_duplicate') != ""): ?>
				<div class="notification msgalert"><a class="close"></a><p><?php echo $this->session->userdata('camp_duplicate'); ?></p></div>
                <?php
				$this->session->unset_userdata('camp_duplicate');
            endif; ?>
            <p>
                <label for="name"><?php echo $this->lang->line('label_inventory_campaign_name'); ?><span style="color:red;">*</span></label>
                <input type="text" name="campname"  id="campname" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_campname'); ?>" value="<?php echo $campaign->campaignname; ?>"/>
                <input type="hidden" name="revcheck" id="revcheck" value="<?php echo $campaign->revenue_type; ?>" />
				<input type="hidden" name="camp_link_count" id="camp_link_count" value="<?php echo $link_count; ?>" />
            </p>
            <p>
                <label for="email"><?php echo $this->lang->line('label_camp_start_date'); ?><span style="color:red;">*</span></label>
                <input type="radio" <?php echo form_text((((set_value('start_date') != '')?set_value('start_date'):$campaign->status_startdate)==0)?"checked":""); ?> onclick="startdate()"  name="start_date" id="start_date" value="0" /> <?php echo $this->lang->line('label_camp_start_immediately'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    <br/>
                    <input type="radio" <?php echo  form_text((((set_value('start_date') != '')?set_value('start_date'):$campaign->status_startdate)==1)?"checked":""); ?> name="start_date" onclick="startdate()" value="1" /> <?php echo $this->lang->line('label_camp_specific_date'); ?>
                    <input id="campstart" name="campstart" type="text" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_camp_start_date'); ?>" readonly="readonly" value="<?php echo date('m/d/Y H:i',strtotime($campaign->activate_time)); ?>"/>
            </p>

               <p>
                <label for="email"><?php echo $this->lang->line('label_camp_end_date'); ?><span style="color:red;">*</span></label>
                 <input type="radio" <?php echo  form_text((((set_value('end_date') != '')?set_value('end_date'):$campaign->status_enddate)==0)?"checked":""); ?> onclick="enddate()" name="end_date" id="end_date" value="0" /> <?php echo $this->lang->line('label_camp_dont_expire'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                         <br/>
                         <input <?php echo  form_text((((set_value('end_date')!='')?set_value('end_date'):$campaign->status_enddate)==1)?"checked":""); ?> type="radio" name="end_date" onclick="enddate()" id="end_date" value="1" /> <?php echo $this->lang->line('label_camp_specific_date'); ?>
                         <input id="campend" name="campend" type="text" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_camp_end_date'); ?>" readonly="readonly" value="<?php echo date('m/d/Y H:i',strtotime($campaign->expire_time)); ?>" />
            </p>

			<?php
				$sel_category	=explode(",", $campaign->catagory);
				$options	=array();
				//$options["0"] =$this->lang->line('label_select_category');
				foreach ($category_list as $obj) { $options[$obj->category_value] =$obj->category_name; } 
			?>
		
			<p>
			   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
			   <?php echo form_multiselect('category[]', $options, @$sel_category, "class='sf' alt='".$this->lang->line('label_select_campaign_category')."'"); ?>
			   <?php echo $this->lang->line('note_for_category_select'); ?>
		   </p>
				
		  <p>
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?><span style="color:red;">*</span></label>
                <select name="pricing_model" id="pricing_model" class="validate[required,custom[integer]]" onchange="revenue_type();">

                    <option value="" <?php echo set_select('pricing_model', ''); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_pricing_model'); ?></option>
                    <?php if($campaign->revenue_type==1){ ?>
                    <option value="1" <?php echo  form_text(set_select('pricing_model', '1',TRUE)); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpm');?></option>
                    <?php } else{ ?>
                    <option value="1" <?php echo  form_text(set_select('pricing_model', '1')); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpm');?></option>
                    <?php } if($campaign->revenue_type==2){ ?>
                    <option value="2" <?php echo  form_text(set_select('pricing_model', '2',TRUE)); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpc');?></option>
                    <?php } else{ ?>
                    <option value="2" <?php echo  form_text(set_select('pricing_model', '2')); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpc');?></option>
                    <?php } if($campaign->revenue_type==3){ ?>
                    <option value="3" <?php echo  form_text(set_select('pricing_model', '3',TRUE)); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpa');?></option>
                    <?php } else{ ?>
                    <option value="3" <?php echo  form_text(set_select('pricing_model', '3')); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpa');?></option>
                    <?php } ?>
                </select>
                <input type="hidden" name="ex_camp_rev_type" id="ex_camp_rev_type" value="<?php echo $campaign->revenue_type; ?>" />
            </p>

                <p>
                <label for="weight"><?php echo $this->lang->line('label_inventory_weight'); ?><span style="color:red;">*</span></label>
                <input type="text" name="weight"  id="weight" class="validate[required,min[1],max[10],custom[integer]] sf" alt="<?php echo $this->lang->line('label_alert_weight'); ?>" 
				value="<?php  echo form_text($campaign->weight); ?>"/>
            </p>

            <p>
                <label for="budget"><?php echo $this->lang->line('label_inventory_daily_budget'); ?><span style="color:red;">*</span></label>
                <input type="text" name="budget"  id="budget" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>" 
				value="<?php echo  form_text($campaign->dailybudget); ?>"/>
            </p>

            <p id="rateimp">
                <label for="name"><?php echo $this->lang->line('label_inventory_rate_impression'); ?><span style="color:red;">*</span></label>
                <input type="text" name="impression"  id="impression" class="validate[required,max[99999999.99],funcCall[rateperimpcheck],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_rate_impression'); ?>"
				 value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p>

            <p id="rateclick">
                <label for="name"><?php echo $this->lang->line('label_inventory_rate_clicks'); ?><span style="color:red;">*</span></label>
                <input type="text" name="clicks"  id="clicks" class="validate[required,max[99999999.99],funcCall[rateperclickcheck],funcCall[Decimalcheck],custom[number]] sf"alt="<?php echo $this->lang->line('label_alert_rate_clicks'); ?>" 
				value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p>

            <p id="rateconv">
                <label for="name"><?php echo $this->lang->line('label_inventory_rate_conversion'); ?><span style="color:red;">*</span></label>
                <input type="text" name="conversion"  id="conversion" class="validate[required,max[99999999.99],funcCall[rateperconcheck],funcCall[Decimalcheck]] sf" alt="<?php echo $this->lang->line('label_alert_rate_conversion'); ?>"  
				value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p>
            <p>
                <button ><?php echo $this->lang->line('label_submit'); ?></button>
                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
            </p>

        </fieldset>
    </div><!--form-->
    </form>
    <?php } ?>
    <script type="text/javascript">
    jQuery(document).ready(function(){
            /**
             * Date picker
            **/
            /*jQuery('#campstart').datetimepicker();
            jQuery('#campend').datetimepicker();*/
			
		/*	var startDateTextBox = jQuery('#campstart');
			var endDateTextBox = jQuery('#campend');

			startDateTextBox.datetimepicker({ 
				minDate: 0,
				maxDate: null,
				onClose: function(dateText, inst) {
					if (endDateTextBox.val() != '') {
						var testStartDate = startDateTextBox.datetimepicker('getDate');
						var testEndDate = endDateTextBox.datetimepicker('getDate');
						if (testStartDate > testEndDate)
							endDateTextBox.datetimepicker('setDate', testStartDate);
					}
					else {
						endDateTextBox.val(dateText);
					}
				},
				onSelect: function (selectedDateTime){
						endDateTextBox.datetimepicker('option', 'minDate', startDateTextBox.datetimepicker('getDate') );
				}
			});
			endDateTextBox.datetimepicker({ 
				minDate: startDateTextBox.val(),
                maxDate: null,
				onClose: function(dateText, inst) {
					if (startDateTextBox.val() != '') {
						var testStartDate = startDateTextBox.datetimepicker('getDate');
						var testEndDate = endDateTextBox.datetimepicker('getDate');
						if (testStartDate > testEndDate)
							startDateTextBox.datetimepicker('setDate', testEndDate);
					}
					else {
						startDateTextBox.val(dateText);
					}
				},
				onSelect: function (selectedDateTime){
					
						startDateTextBox.datetimepicker('option', 'maxDate', endDateTextBox.datetimepicker('getDate') );
				}
			});
			*/
			
		jQuery("#campstart").datetimepicker(
		{
				minDate: 0,
				maxDate: null,
				//dateFormat: "dd/mm/yy",
				timeFormat: "hh:mm",
				onSelect: function(selectedDateTime, inst) {

					var theDate = jQuery("#campstart").datetimepicker("getDate");
					/*
					* Alternative syntax
					* var theDate = $.datepicker.parseDateTime("dd/mm/yy", "hh:mm:ss", selectedDateTime);
					*/
					
					if(theDate != null) {
						var str_old_datetime = jQuery("#campend").val();

						jQuery("#campend").datetimepicker("option", "minDate", theDate);
						jQuery("#campend").datetimepicker("option", "minDateTime", theDate);

						jQuery("#campend").val(str_old_datetime);
					}
				}
			}
		);
		
	jQuery("#campend").datetimepicker(
    {
			minDate: jQuery("#campstart").val(),
            maxDate: null,
			//dateFormat: "mm/mm/yy",
			timeFormat: "hh:mm",
			onSelect: function(selectedDateTime, inst) {

				var theDate = jQuery("#campend").datetimepicker("getDate");
				/*
				* Alternative syntax
				* var theDate = $.datepicker.parseDateTime("dd/mm/yy", "hh:mm:ss", selectedDateTime);
				*/
				
				if(theDate != null) {
					var str_old_datetime = jQuery("#campstart").val();

					jQuery("#campstart").datetimepicker("option", "maxDate", theDate);
					jQuery("#campstart").datetimepicker("option", "maxDateTime", theDate);
					jQuery("#campstart").val(str_old_datetime);
				}
			}
		}
	);	
			
		/*	jQuery( "#campstart" ).datetimepicker({
								 timeFormat: "hh:mm",
								 minDate: 0,
								 maxDate: null,
								 onSelect: function(selected) {
										jQuery("#campend").datetimepicker("option","minDate", selected)
        						}
								 
                		 });
			jQuery( "#campend" ).datetimepicker({
								timeFormat: "hh:mm",
								minDate: 0,
                    			maxDate: null,
								 onSelect: function(selected) {
          								jQuery("#campstart").datetimepicker("option","maxDate", selected)
								}
                		 });
			*/
			
    });

    startdate();
    enddate();
    /* Select Campaign Running Date */
    function startdate(){
                    if(jQuery('input[name=start_date]:checked', '#form_edit_campaign').val()=="1")
                    {
                            jQuery('#campstart').show();
                    }
                    else
                    {
                            jQuery('#campstart').hide();
                    }
            }

    function enddate(){
                    if(jQuery('input[name=end_date]:checked', '#form_edit_campaign').val()=="1")
                    {
                            jQuery('#campend').show();
                    }
                    else
                    {
                            jQuery('#campend').hide();
                    }
            }
     
     /* Display Revenue Type Fields */
     function revenue_type()
     {
        var revenue = document.getElementById('pricing_model').value;
        //alert(revenue);
        if(revenue!='')
        {
            if(revenue==1)
            {
                jQuery('#rateimp').show();
                jQuery('#rateclick').hide();
                jQuery('#rateconv').hide();
            }
            else if(revenue==2)
            {
                jQuery('#rateclick').show();
                jQuery('#rateimp').hide();
                jQuery('#rateconv').hide();
            }
            else if(revenue==3)
            {
                jQuery('#rateconv').show();
                jQuery('#rateimp').hide();
                jQuery('#rateclick').hide();
            }
        }
        else
        {
            jQuery('#rateconv').hide();
            jQuery('#rateimp').hide();
            jQuery('#rateclick').hide();
        }
     }
     </script>
