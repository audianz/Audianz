<script type="text/javascript">
jQuery(document).ready(function() {
   
   		 var date = new Date();
                 var currentMonth = date.getMonth();
                 var currentDate = date.getDate();
                 var currentYear = date.getFullYear();
 
     /*** Date picker ***/

	jQuery( "#from_date" ).datepicker({

								 minDate:null,

								 maxDate: 0,

								   onSelect: function(selected) {

								   		jQuery("#to_date").datepicker("option","minDate", selected)

        						}

								 

                		 });

	jQuery( "#to_date" ).datepicker({

								minDate:null,

                    			 maxDate: 0,

								 onSelect: function(selected) {

          								jQuery("#from_date").datepicker("option","maxDate", selected)

        						}

                		 });
	
});
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('label_report_subtitle');?></h1>
<form id="report"  name="report"  method="post" action="<?php echo site_url('advertiser/reports/Export_excel_reports');?>">
        

<?php
	$searchObj = $this->session->userdata('statistics_search_arr');
?>

        	<div class="form_default">
                <fieldset>
                <legend><?php echo $this->lang->line('label_report_title');?></legend>

<p>
<label for="name"><?php echo $this->lang->line('label_reports_date');?></label>
     <?php
$options_arr = array(										
												"today"=>$this->lang->line('lang_statistics_today'),
												"yesterday"=>$this->lang->line('lang_statistics_yesterday'),
												"thisweek"=>$this->lang->line('lang_statistics_this_week'),
												"last7days"=>$this->lang->line('lang_statistics_last_sev_day'),
												"thismonth"=>$this->lang->line('lang_statistics_this_month'),
												"lastmonth"=>$this->lang->line('lang_statistics_last_month'),
												/*"specific_date"=>$this->lang->line('lang_statistics_spec_date'),*/
												"all"=>$this->lang->line('lang_statistics_all_stats')
											);
$sel_val = (set_value('search_field') != '')?set_value('search_field'):$search_type;
								echo form_dropdown('search_field',$options_arr,$sel_val,'id="search_field" onChange="show_date(this.value);"'); 
?>


<span id="specificDataSec" style=" <?php echo ($sel_val=="specific_date")?"":"display:none"; ?>" >     
								<?php echo $this->lang->line('lang_statistics_advertiser_from_date');?>
								<input id="from_date" name="from_date" readonly="true" type="text" value="" size="10" width="100" class="width100" /> 
								<?php echo $this->lang->line('lang_statistics_advertiser_to_date');?>
								<input id="to_date"  name="to_date" readonly="true" type="text" value="" size="10" width="100" class="width100" /> 
								
							</span>
						 

                     
 </p>

<p><label for="name"><?php echo $this->lang->line('label_reports_Website');?></label></p>		
<p>
	<select name="website" onchange="get_website_campaign(this.value)">
	<option value="all"><?php echo $this->lang->line('label_reports_all_website'); ?></option>
	<?php
	if(!empty($website))
	{
		foreach($website as $web)
		{?>
		<option value="<?php echo $web->affiliateid;?>"><?php echo $web->name;?></option>
		<?php
		}
	}else
	{?>
		<option value="-1">No Websites</option>
		
	<?php } ?>
	</select>

</p>
<p><label for="name"><?php echo $this->lang->line('label_reports_Campaign');?></label></p>
<p>
<div id="website_campaign">
	<select name="campaign" id="camp">
	<option value="all"><?php echo $this->lang->line('label_reports_all_campaign'); ?></option>
	<?php
	/*foreach($campaign as $cam)
	{?>
	<option value="<?php echo $cam->campaignid;?>"><?php echo $cam->campaignname;?></option>
	<?php
	}*/
	?>
    </select>
</div>


</p>



<p>
<label for="name"></label>
	<button style='margin-left:10px'><?php echo $this->lang->line('label_reports_generate');?></button>
	<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
</p>
			
                    
<script>
function show_date(selVal){

	if(selVal == "specific_date"){
		jQuery("#specificDataSec").show();
	}
	else
	{
		jQuery("#specificDataSec").hide();
		//document.getElementById('search_form').submit();
	}
}
function get_website_campaign(selVal)
{
	
	if(selVal !="all") 
	{
		jQuery.ajax({ cache: false,
		type : "POST",
		url: '<?php echo site_url('advertiser/reports/get_website_campaign')?>',
		data: "website="+selVal,
			success : function (data) {
			jQuery('#website_campaign').html(data);
			}
		});
	}
	else
	{
		jQuery('#website_campaign').html('<select name="campaign" id="camp"><option value="all"><?php echo $this->lang->line('label_reports_all_campaign'); ?></select>');
		}
	
}
</script>		
      
   </div><!--form-->
            
        
        </form>
</div>

<script type="text/javascript">

	function goToList()
	{
		document.location.href='<?php echo site_url('advertiser/dashboard'); ?>';
	}
</script>
