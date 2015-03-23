<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/elements.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/form_validation/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-timepicker-addon.css" type="text/css"/>

<script type="text/javascript">
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#form_add_campaign").validationEngine();

                jQuery('#rateimp').hide();
                jQuery('#rateclick').hide();
                jQuery('#rateconv').hide();
                jQuery('#rtbCamp').hide();
               // jQuery('#bidrateimp').hide();
				//jQuery('#bidrateclick').hide();
				
				jQuery('#intermediary').hide();
				
				
				
				var revenue = document.getElementById('pricing_model').value;
                
                if(revenue==1)
                {
                    jQuery('#rateimp').show();
                    jQuery('#rateclick').hide();
                    jQuery('#rateconv').hide();
                    jQuery('#rtbCamp').show();
                }
                else if(revenue==2)
                {
                    jQuery('#rateimp').hide();
                    jQuery('#rateclick').show();
                    jQuery('#rateconv').hide();
                    jQuery('#rtbCamp').show();
                }
                else if(revenue==3)
                {
                    jQuery('#rateimp').hide();
                    jQuery('#rateclick').hide();
                    jQuery('#rateconv').show();
                    jQuery('#rtbCamp').hide();
                }
	});

    function goToList()
    {
        document.location.href='<?php echo site_url('admin/inventory_campaigns'); ?>';
    }

    function fillAdv(advval)
    {
		//var advval = document.getElementById('advertiserlist').value;
		document.getElementById('advertiser').value = advval;
	}
	
	function validate_date()
	{
		var form = document.form_add_campaign;
		var start = form.start_date[0].value;
		var start1 = form.start_date[1].value;
		var end = form.end_date[0].value;
		var end1 = form.end_date[1].value;
		if(start==0 && end==0)
		{
		}
		if(start==0 && end1==1)
		{
			var days,months;
			var currentTime = new Date();
			var month = currentTime.getMonth() + 1;
			var day = currentTime.getDate();
			var year = currentTime.getFullYear()
			if(month>9)
			{
				months=month;
			}
			else
			{
				months="0"+month;
			}
			if(day>9)
			{
				days=day;
			}
			else
			{
				days="0"+day;
			}
			final_startdate=year + "/" + months + "/" +days;
			var selectend=document.getElementById("campend").value;
			eyear=selectend.substring(6,10);
			emonth=selectend.substring(0,2);
			eday=selectend.substring(3,5);
			final_enddate=eyear + "/" + emonth + "/" +eday;
			var date1=new Date(final_startdate);
			var date2=new Date(final_enddate);
			var oneDay=1000*60*60*24;
			var check=Math.ceil((date2.getTime()-date1.getTime())/oneDay);
			if(check<0)
			{
				jAlert('<?php echo $this->lang->line('label_end_date_error'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>');
				return false;
			}
		}
		if(start1==1 && end1==0)
		{
		}
		if(start1==1 && end1==1)
		{
			var days,months;
			var selectstart=document.getElementById("campstart").value;
			smonth=selectstart.substring(0,2);
			sday=selectstart.substring(3,5);
			syear=selectstart.substring(6,10);
			final_startdate=syear + "/" + smonth + "/" +sday;
			var selectend=document.getElementById("campend").value;
			eyear=selectend.substring(6,10);
			emonth=selectend.substring(0,2);
			eday=selectend.substring(3,5);
			final_enddate=eyear + "/" + emonth + "/" +eday;
			var date1=new Date(final_startdate);
			var date2=new Date(final_enddate);
			var oneDay=1000*60*60*24;
			var check=Math.ceil((date2.getTime()-date1.getTime())/oneDay);
			
			if(check<0)
			{
				//alert("Startdate is greater than End date");
				jAlert('<?php echo $this->lang->line('label_end_date_error'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>');
				return false;
			}
		}
		//}
		
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

	   var cpm=value/1000;
		
	
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
<style type="text/css">
/* css for timepicker */
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
.ui-timepicker-div dl { text-align: left; }
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
.ui-timepicker-div td { font-size: 90%; }
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
<form id="form_add_campaign" name="form_add_campaign" action="<?php echo site_url('advertiser/campaigns/add_process'); ?>" method="post" >
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_add_campaign'); ?></h1>
        <div class="form_default">
        <fieldset>
            <legend><?php echo $this->lang->line('label_inventory_add_campaign'); ?></legend>
            <?php echo validation_errors();
            if($this->session->userdata('camp_error') != ""):
            ?>
				<div class="notification msgerror"><a class="close"></a><?php echo $this->session->userdata('camp_error'); ?></div>
				<?php $this->session->unset_userdata('camp_error');
            endif;
			
		if($this->session->userdata('camp_duplicate') != ""): ?>
			<div class="notification msgalert"> <a class="close"></a> <?php echo $this->session->userdata('camp_duplicate'); ?></div>
			<?php $this->session->unset_userdata('camp_duplicate');
		endif; ?>
			
            <p>
                <label for="name"><?php echo $this->lang->line('label_inventory_campaign_name'); ?> <span style="color:red;" >*</span></label>
                <input type="text" name="campname"  id="campname" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_campname'); ?>" value="<?php echo  form_text(set_value('campname')); ?>"/>
            </p>
            <p>
                <label for="email"><?php echo $this->lang->line('label_camp_start_date'); ?> <span style="color:red;" >*</span></label>
                <input type="radio" onclick="startdate()"  name="start_date" id="start_date" value="0" <?php echo set_radio('start_date', '0',TRUE); ?> /> <?php echo $this->lang->line('label_camp_start_immediately'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                    <br/>
                    <input type="radio" name="start_date" onclick="startdate()" value="1" <?php echo set_radio('start_date', '1'); ?> /> <?php echo $this->lang->line('label_camp_specific_date'); ?>
                    <input id="campstart" name="campstart" type="text" class="validate[required]" readonly="readonly" alt="<?php echo $this->lang->line('label_alert_camp_start_date'); ?>"
					value="<?php echo  form_text(set_value('campstart')); ?>"/>
            </p>

			<p>
                <label for="email"><?php echo $this->lang->line('label_camp_end_date'); ?> <span style="color:red;" >*</span></label>
                 <input type="radio" checked="checked" onclick="enddate()" name="end_date" id="end_date" value="0" <?php echo set_radio('end_date', '0', TRUE); ?>/> <?php echo $this->lang->line('label_camp_dont_expire'); ?> &nbsp;&nbsp;&nbsp;&nbsp;
                         <br/>
                         <input type="radio" name="end_date" onclick="enddate()" id="end_date" value="1" <?php echo set_radio('end_date', '1'); ?>/> <?php echo $this->lang->line('label_camp_specific_date'); ?>
                         <input id="campend" name="campend" type="text" class="validate[required]" readonly="readonly" alt="<?php echo $this->lang->line('label_alert_camp_end_date'); ?>"
						  value="<?php echo  form_text(set_value('campend')); ?>" />
            </p>

			<?php
				$sel_category	='';
				$options	=array();
				//$options["0"] =$this->lang->line('label_select_category');
				if(!empty($category_list)){
					foreach ($category_list as $obj) { $options[$obj->category_id] =$obj->category_name; } 
				}
			?>
		
			<p>
			   <label for="category"><?php echo $this->lang->line('label_campaign_category'); ?></label>
			   <?php echo form_multiselect('category[]', $options, $sel_category, "class='sf' alt='".$this->lang->line('label_select_campaign_category')."'"); ?>
			   <?php echo $this->lang->line('note_for_category_select'); ?>
		   </p>
		   
		   
		   
				<p>
                <label for="pricing_model"><?php echo $this->lang->line('label_inventory_pricing_model'); ?> <span style="color:red;" >*</span></label>
                <select name="pricing_model" id="pricing_model" class="validate[required] " alt="<?php echo $this->lang->line("label_alert_pricing_model"); ?>" onchange="revenue_type();">
                    <option value="" <?php echo set_select('pricing_model', '', TRUE); ?>><?php echo $this->lang->line('label_choose').' '.$this->lang->line('label_inventory_pricing_model'); ?></option>
                    <option value="1" <?php echo set_select('pricing_model', '1'); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpm');?></option>
                    <option value="2" <?php echo set_select('pricing_model', '2'); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpc');?></option>
                    <option value="3" <?php echo set_select('pricing_model', '3'); ?>><?php echo $this->lang->line('label_advertiser_campaign_cpa');?></option>
                </select>
            </p>
            
            <p id="rtbCamp">
				<label for="rtb"><?php echo "Real-Time Bidding"; ?></label>
                <input type="checkbox" name="rtb" id="rtb" onchange="selRTBcamp();" />&nbsp;&nbsp;Yes, I want to make this campign with Real-Time Bidding
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
                <label style="padding-top: 4.5%" >Keyword 1<span style="color:red;" >*</span></label>
                <textarea name="key1"  id="key1" style="width:15%"  class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>"  ><?php echo $keywords[0]->keywords1 ?></textarea>
				
				<label  style="width: 80px;float:none;margin-left:3%;margin-top:20%;text-align: right; " >Keyword 2<span style="color:red;" >*</span></label>
				<textarea name="key2"  id="key2" style="width:15%" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>"  ><?php echo $keywords[0]->keywords2 ?></textarea>
				
				<label  style="width: 80px;float:none;margin-left:5%;padding-bottom:5%" >Keyword 3<span style="color:red;" >*</span></label>
				<textarea name="key3"  id="key3" style="width:15%" class="validate[required] sf" alt="<?php echo $this->lang->line('label_alert_budget'); ?>"  ><?php echo $keywords[0]->keywords3 ?></textarea>
            </p>
            
          
            <p id="rateimp">
                <label for="impr"><?php echo "CPM Rate" # echo $this->lang->line('label_inventory_rate_impression'); ?> <span style="color:red;" >*</span></label>
                <input type="text" name="impression"  id="impression" class="validate[required,max[99999999.99],funcCall[Decimalcheck],custom[number]] sf"alt="<?php echo $this->lang->line('label_alert_rate_impression'); ?>"
				value="<?php echo  form_text(set_value('impression')); ?>"/>
            </p>

            <p id="rateclick">
                <label for="click"><?php echo $this->lang->line('label_inventory_rate_clicks'); ?> <span style="color:red;" >*</span></label>
                <input type="text" name="clicks"  id="clicks" class="validate[required,max[99999999.99],funcCall[rateperclickcheck],funcCall[Decimalcheck],custom[number]] sf"alt="<?php echo $this->lang->line('label_alert_rate_clicks'); ?>"
				value="<?php echo  form_text(set_value('clicks')); ?>"/>
            </p>
            
           <p id="rateconv">
                <label for="name"><?php echo $this->lang->line('label_inventory_rate_conversion'); ?> <span style="color:red;" >*</span></label>
                <input type="text" name="conversion"  id="conversion" class="validate[required,max[99999999.99],funcCall[rateperconcheck],funcCall[Decimalcheck],custom[number]] sf" alt="<?php echo $this->lang->line('label_alert_rate_conversion'); ?>"
				value="<?php echo  form_text(set_value('conversion')); ?>"/>
            </p>
            <p>
                <button><?php echo $this->lang->line('label_submit'); ?></button>
                <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
            </p>

        </fieldset>
    </div><!--form-->
    </form>
    <script type="text/javascript">
    jQuery(document).ready(function(){
            /**
             * Date picker
            **/
            /*jQuery('#campstart').datetimepicker({ minDate: +0 });
			
			jQuery('#campend').datetimepicker({ minDate: +0 });*/
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
                    if(jQuery('input[name=start_date]:checked', '#form_add_campaign').val()=="1")
                    {
                            jQuery('#campstart').show();
                    }
                    else
                    {
                            jQuery('#campstart').hide();
                    }
            }

    function enddate(){
                    if(jQuery('input[name=end_date]:checked', '#form_add_campaign').val()=="1")
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
                //jQuery('#bidrateimp').hide();
               // jQuery('#bidrateclick').hide();
            }
            else if(revenue==2)
            {
                jQuery('#rateclick').show();
                jQuery('#rateimp').hide();
                jQuery('#rateconv').hide();
                jQuery('#rtbCamp').show();
               // jQuery('#bidrateimp').hide();
               // jQuery('#bidrateclick').hide();
            }
            else if(revenue==3)
            {
                jQuery('#rateconv').show();
                jQuery('#rateimp').hide();
                jQuery('#rateclick').hide();
                jQuery('#rtbCamp').hide();
               // jQuery('#bidrateimp').hide();
               // jQuery('#bidrateclick').hide();
            }
        }
        else
        {
            jQuery('#rateconv').hide();
            jQuery('#rateimp').hide();
            jQuery('#rateclick').hide();
            //jQuery('#bidrateimp').hide();
           // jQuery('#bidrateclick').hide();
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

