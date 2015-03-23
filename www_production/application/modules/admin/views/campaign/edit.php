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
					jQuery('#rtbCamp').show();
					//jQuery('#bidrateimp').hide();
					//jQuery('#bidrateclick').hide();
				}
				else if(revenue==2)
				{
					jQuery('#rateclick').show();
					jQuery('#rateimp').hide();
					jQuery('#rateconv').hide();
					jQuery('#rtbCamp').show();
					//jQuery('#bidrateimp').hide();
					//jQuery('#bidrateclick').hide();
				}
				else if(revenue==3)
				{
					jQuery('#rateconv').show();
					jQuery('#rateimp').hide();
					jQuery('#rateclick').hide();
					jQuery('#rtbCamp').hide();
					//jQuery('#bidrateimp').hide();
					//jQuery('#bidrateclick').hide();
				}
						
			var rtbval = document.getElementById('rtb').checked;
		 var revenue = document.getElementById('pricing_model').value;
		 //alert(revenue);
		 if(rtbval==true)
		 {			
			if(revenue!='')
			{
				if(revenue==1)
				{
					//jQuery('#bidrateimp').show();
					//jQuery('#rateimp').hide();	
					jQuery("label[for='impr']").text("Bid CPM Rate");
					jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');				
				}
				else if(revenue==2)
				{
					//jQuery('#bidrateclick').show();
					//jQuery('#rateclick').hide();
					jQuery("label[for='click']").text("Bid Rate/Click");
					jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
				}
				else
				{
					jQuery("label[for='impr']").text("CPM Rate");
					jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');
					jQuery("label[for='click']").text("Rate/Click");
					jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
						
				}
			}
				
		 } 
		 else
		 {
			if(revenue==1)
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').show();
				jQuery('#rateclick').hide();
				jQuery("label[for='impr']").text("CPM Rate");
				jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();					
			}
			else if(revenue==2)
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').show();
				jQuery("label[for='click']").text("Rate/Click");
				jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
			else if(revenue==3)
			{
				jQuery('#rateconv').show();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').hide();
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
			else
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').hide();
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
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
			 return "CPM Rate should less than Daily Budget";
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
<form id="form_edit_campaign" name="form_edit_campaign" action="<?php echo site_url('admin/inventory_campaigns/edit_campaign_process/'.$campaignidpro); ?>" method="post" >
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
                    <input id="campstart" name="campstart" type="text" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_camp_start_date'); ?>" readonly="readonly" value="<?php echo isset($campaign->activate_time)?date('m/d/Y H:i',strtotime($campaign->activate_time)):''; ?>"/>
            </p>

               <p>
                <label for="email"><?php echo $this->lang->line('label_camp_end_date'); ?><span style="color:red;">*</span></label>
                 <input type="radio" <?php echo  form_text((((set_value('end_date') != '')?set_value('end_date'):$campaign->status_enddate)==0)?"checked":""); ?> onclick="enddate()" name="end_date" id="end_date" value="0" /> <?php echo $this->lang->line('label_camp_dont_expire'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                         <br/>
                         <input <?php echo  form_text((((set_value('end_date')!='')?set_value('end_date'):$campaign->status_enddate)==1)?"checked":""); ?> type="radio" name="end_date" onclick="enddate()" id="end_date" value="1" /> <?php echo $this->lang->line('label_camp_specific_date'); ?>
                         <input id="campend" name="campend" type="text" class="validate[required]" alt="<?php echo $this->lang->line('label_alert_camp_end_date'); ?>" readonly="readonly" value="<?php echo isset($campaign->expire_time)?date('m/d/Y H:i',strtotime($campaign->expire_time)):''; ?>" />
            </p>

			<?php
				$sel_category	=explode(",", $campaign->catagory);
				$options	=array();
				//$options["0"] =$this->lang->line('label_select_category');
				foreach ($category_list as $obj) { $options[$obj->category_id] =$obj->category_name; } 
			?>
		
			<p>
			   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
			   <?php echo form_multiselect('category[]', $options, @$sel_category, "class='sf' alt='".$this->lang->line('label_select_campaign_category')."'"); ?>
			   <?php echo $this->lang->line('note_for_category_select'); ?>
		   </p>
				
		  <p>
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?><span style="color:red;">*</span></label>
                <select name="pricing_model" id="pricing_model" class="validate[required] " alt="<?php echo $this->lang->line("label_alert_pricing_model"); ?>" onchange="revenue_type();">

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
			<p id="rtbCamp">
				<label for="rtb"><?php echo "Real-Time Bidding"; ?></label>
                <input type="checkbox" name="rtb" id="rtb" onchange="selRTBcamp();" <?php if($campaign->rtb==1): ?> checked="checked" <?php endif; ?>/>&nbsp;&nbsp;Yes, I want to make this campaign with Real-Time Bidding
		
            
            <p>
                <label for="name"><?php echo $this->lang->line('label_promotion_msg'); ?><span style="color:red;">*</span></label>
				<textarea name="promote_msg"  id="promote_msg" style="width:24%" ><?php echo $campaign->promote_msg; ?></textarea>
            </p>
            

            <p>
				<label for="weight" ><?php echo $this->lang->line('label_inventory_weight'); ?><span style="color:red;">*</span></label>
                <input type="text" name="weight"  id="weight" style="width:15%" class="validate[required,min[1],max[10],custom[integer]] sf" alt="<?php echo $this->lang->line('label_alert_weight'); ?>" 
				value="<?php  echo form_text($campaign->weight); ?>"/>
               
                <label for="budget" style="width: 80px;float:none;margin-left:2%;margin-top:20%;text-align: right; " ><?php echo $this->lang->line('label_inventory_daily_budget'); ?><span style="color:red;">*</span></label>
                <input type="text" name="budget"  id="budget" style="width:15%" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>" 
				value="<?php echo  form_text($campaign->dailybudget); ?>"/>
				
				<label for="impressions" style="width: 80px;float:none;margin-left:1%;margin-top:20%;text-align: right; ">Total Impressions <span style="color:red;" >*</span></label>
                <input type="text" name="total_impressions"  id="total_impressions" style="width:15%" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>"
				value="<?php echo $campaign->total_imp; ?>"  />
            </p>
            
            
            
            
            <p>
                <label style="padding-top: 4.5%" >Keyword 1</label>
                <textarea name="key1"  id="key1" style="width:15%"  class="validate[required] sf"   ><?php echo $keywords[0]->keywords1 ?></textarea>
				
				<label  style="width: 80px;float:none;margin-left:3%;margin-top:20%;text-align: right; " >Keyword 2</label>
				<textarea name="key2"  id="key2" style="width:15%" class="validate[required] sf"   ><?php echo $keywords[0]->keywords2 ?></textarea>
				
				<label  style="width: 80px;float:none;margin-left:5%;padding-bottom:5%" >Keyword 3</label>
				<textarea name="key3"  id="key3" style="width:15%" class="validate[required] sf"   ><?php echo $keywords[0]->keywords3 ?></textarea>
            </p>
            
             
            <p id="rateimp">
                <label for="impr"><?php echo "CPM Rate"; ?><span style="color:red;">*</span></label>
                <input type="text" name="impression"  id="impression" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_rate_impression'); ?>"
				 value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p>
            

            <p id="rateclick">
                <label for="click">Rate/Click<span style="color:red;">*</span></label>
                <input type="text" name="clicks"  id="clicks" class="validate[required,max[99999999.99],funcCall[rateperclickcheck],funcCall[Decimalcheck],custom[number]] sf"alt="<?php echo $this->lang->line('label_alert_rate_clicks'); ?>" 
				value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p>
            
            <?php /* ?><p id="bidrateimp">
                <label for="name"><b><?php echo "Bid Rate/Impression"; ?> <span style="color:red;" >*</span></b></label>
                <input type="text" name="impression"  id="impression" class="validate[required,max[99999999.99],funcCall[rateperimpcheck],funcCall[Decimalcheck]] sf" alt="<?php echo $this->lang->line('label_alert_rate_impression'); ?>" value="<?php echo form_text($campaign->revenue); ?>" />
            </p>

            <p id="bidrateclick">
                <label for="name"><b><?php echo "Bid Rate/Click"; ?> <span style="color:red;" >*</span></b></label>
                <input type="text" name="clicks"  id="clicks" class="validate[required,max[99999999.99],funcCall[rateperclickcheck],funcCall[Decimalcheck]] sf" alt="<?php echo $this->lang->line('label_alert_rate_clicks'); ?>" value="<?php echo  form_text($campaign->revenue); ?>"/>
            </p><?php */ ?>

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
            
			
		jQuery( "#campstart" ).datetimepicker({
			minDate: 0,
			maxDate: null,
			onSelect: function(selected) {
			jQuery("#campend").datepicker("option","minDate", selected)
			}
		});
		jQuery( "#campend" ).datetimepicker({
			minDate: 0,
			maxDate: null,
			onSelect: function(selected) {
			jQuery("#campstart").datepicker("option","maxDate", selected)
			}
		});
			
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
                jQuery('#rtbCamp').show();
               // jQuery('#bidrateimp').hide();
                //jQuery('#bidrateclick').hide();
            }
            else if(revenue==2)
            {
                jQuery('#rateclick').show();
                jQuery('#rateimp').hide();
                jQuery('#rateconv').hide();
                jQuery('#rtbCamp').show();
                //jQuery('#bidrateimp').hide();
               // jQuery('#bidrateclick').hide();
            }
            else if(revenue==3)
            {
                jQuery('#rateconv').show();
                jQuery('#rateimp').hide();
                jQuery('#rateclick').hide();
                jQuery('#rtbCamp').hide();
               // jQuery('#bidrateimp').hide();
                //jQuery('#bidrateclick').hide();
            }
        }
        else
        {
            jQuery('#rateconv').hide();
            jQuery('#rateimp').hide();
            jQuery('#rateclick').hide();
            //jQuery('#bidrateimp').hide();
            //jQuery('#bidrateclick').hide();
        }
     }
     
      function selRTBcamp(val)
     {
		var rtbval = document.getElementById('rtb').checked;
		 var revenue = document.getElementById('pricing_model').value;
		 //alert(revenue);
		 if(rtbval==true)
		 {			
			if(revenue!='')
			{
				if(revenue==1)
				{
					//jQuery('#bidrateimp').show();
					//jQuery('#rateimp').hide();	
					jQuery("label[for='impr']").text("Bid Rate/Impression");
					jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');				
				}
				else if(revenue==2)
				{
					//jQuery('#bidrateclick').show();
					//jQuery('#rateclick').hide();
					jQuery("label[for='click']").text("Bid Rate/Click");
					jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
				}
				else
				{
					jQuery("label[for='impr']").text("Rate/Impression");
					jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');
					jQuery("label[for='click']").text("Rate/Click");
					jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
						
				}
			}
				
		 } 
		 else
		 {
			if(revenue==1)
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').show();
				jQuery('#rateclick').hide();
				jQuery("label[for='impr']").text("Rate/Impression");
				jQuery("label[for='impr']").append('<span style="color:red;"> *</span>');
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();					
			}
			else if(revenue==2)
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').show();
				jQuery("label[for='click']").text("Rate/Click");
				jQuery("label[for='click']").append('<span style="color:red;"> *</span>');
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
			else if(revenue==3)
			{
				jQuery('#rateconv').show();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').hide();
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
			else
			{
				jQuery('#rateconv').hide();
				jQuery('#rateimp').hide();
				jQuery('#rateclick').hide();
				//jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();	
			}
		 }	 
	}
     </script>
