<link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/style.css" />

<!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie7.css"/>
<![endif]-->

<script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
   
   				 var date = new Date();
                 var currentMonth = date.getMonth();
                 var currentDate = date.getDate();
                 var currentYear = date.getFullYear();
 
    /**
	 * Date picker
	**/
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

function open_toggle(adv_id)
{		
	if(jQuery("#child_row"+adv_id).is(":visible")) {
	jQuery("#child_row"+adv_id).toggle('very slow');
		var imgsrc ='<?php echo base_url("assets/images/icons/toggle_up.jpeg"); ?>';
		jQuery("#test"+adv_id).attr('src', imgsrc);
		jQuery("#child_content_"+adv_id).hide();
	}
	else {
		jQuery("#child_row"+adv_id).toggle('very slow');
		var imgsrc ='<?php echo base_url("assets/images/icons/toggle_down.jpeg"); ?>';
		jQuery("#test"+adv_id).attr('src', imgsrc);
		
		jQuery.post('<?php echo site_url('admin/statistics_advertiser/view_more_details'); ?>', {'advertiser_id': adv_id}, function(response) {
			//document.getElementById("child_conetnt_"+adv_id).innerHTML = response;
			
			jQuery("#child_conetnt_"+adv_id).html(response);			
			jQuery("#child_conetnt_"+adv_id).show();			
	    });
		
	}	  
}

function show_date(selVal){
	if(selVal == "specific_date"){
		jQuery("#specificDataSec").show();
	}
	else
	{
		jQuery("#specificDataSec").hide();
		document.getElementById('search_form').submit();
	}
	
}	

</script>
<?php
	$searchObj = $this->session->userdata('statistics_search_arr');
?>

<div id="statistics_title">
<h1 class="pageTitle"><?php echo $this->lang->line('lang_statistics_advertiser_statistics');?></h1>
<?php

if(!empty($stat_data['stat_list']))
{?>
<a href="<?php echo site_url('admin/statistics_advertiser/export_hour_wise');?>" title="<?php echo $this->lang->line('lang_export_excel_ad_title_as_hour_wise');?>"><span class="export_excel_link"><?php echo $this->lang->line('lang_statistics_export');?></span></a>
</div>
<br/><br/>
<?php }?>

		<form id="search_form" action="<?php echo site_url("admin/statistics_advertiser/view"); ?>" method="post">

        
        	<div class="form_default">
                <fieldset style="padding:5px;">
                	<div style="width:100%;height:50px;padding-top:10px;">
						<div style="width:65%;height:50px;float:left;vertical-align:bottom;">
							<span style="margin:10px;" ><?php echo $this->lang->line('lang_statistics_advertiser_date');?></span>
							<?php
								$options_arr = array(
												"all"=>$this->lang->line('lang_statistics_all_stats'),
												"today"=>$this->lang->line('lang_statistics_today'),
												"yesterday"=>$this->lang->line('lang_statistics_yesterday'),
												"thisweek"=>$this->lang->line('lang_statistics_this_week'),
												"last7days"=>$this->lang->line('lang_statistics_last_sev_day'),
												"thismonth"=>$this->lang->line('lang_statistics_this_month'),
												"lastmonth"=>$this->lang->line('lang_statistics_last_month'),
												"specific_date"=>$this->lang->line('lang_statistics_spec_date')
											);
							
								$sel_val = (set_value('search_field') != '')?set_value('search_field'):$searchObj['search_type'];
								echo form_dropdown('search_field', $options_arr,$sel_val,"onchange='show_date(this.value)' id='search_field' alt='".$this->lang->line('label_enter_advertiser')."'"); 
							?>
							<span id="specificDataSec" style=" <?php echo ($sel_val=="specific_date")?"":"display:none"; ?>" >     
								<?php echo $this->lang->line('lang_statistics_advertiser_from_date');?>
								<input id="from_date" name="from_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" size="10" width="100" class="width100" /> 
								<?php echo $this->lang->line('lang_statistics_advertiser_to_date');?>
								<input id="to_date"  name="to_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" size="10" width="100" class="width100" /> 
								<button style='margin-left:10px'><?php echo $this->lang->line('lang_statistics_advertiser_search');?></button>
							</span>
						</div>
						<div style="width:35%;height:50px;float:right;vertical-align:bottom;">
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_adv');?> :</strong> <?php echo substr($stat_adv_det[0]->advertiser_name,0,40); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
							<?php if(isset($stat_camp_data)): ?>
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_campaigns');?> :</strong> <?php echo substr($stat_camp_data->campaignname,0,40); ?>
							<?php endif; ?>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php if(isset($stat_banner_data)): ?>
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_banners');?> :</strong> <?php echo substr($stat_banner_data->description,0,40); ?>
							<?php endif; ?>
							<br/>
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_date');?> :</strong> <?php echo date("m/d/Y",strtotime($searchObj['sel_date']));  ?> 
							</div>
					</div>
				</fieldset>
            </div><!--form-->
        </form>
		
<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="adv_stat">
    <thead>
        <tr>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_horu');?></th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_impression');?> (<?php echo $stat_data['tot_val']['IMP']; ?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_clicks');?> (<?php echo $stat_data['tot_val']['CLK']; ?>)</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_conversions');?> (<?php echo $stat_data['tot_val']['CON']; ?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_ctr');?> (<?php echo number_format($stat_data['tot_val']['CTR'],2,".",","); ?>%)</th>
        </tr>
    </thead>
    <colgroup>
        <col class="con0" />
        <col class="con1" />
        <col class="con0" />
    	<col class="con1" />
    	<col class="con0" />
    </colgroup>
    <tbody>
    	<?php
    	if(count($stat_data['stat_list']) > 0):
		foreach($stat_data['stat_list'] as $time_key=>$objStat):
		?>
		<tr class="gradeX">
		<?php list($time) = explode(':',$time_key); ?> 
		  <td><?php echo date('H:i',mktime($time,0)); ?>-<?php echo date('H:i',mktime($time,59)); ?></td>
		  <td><?php echo $objStat['IMP']; ?></td>
		  <td><?php echo $objStat['CLK']; ?></td>
		  <td><?php echo $objStat['CON']; ?></td>
		  <td><?php echo $objStat['CTR']; ?>%</td>
		</tr>
		<?php
		endforeach;
		else:
		?>	
		<tr><td align="center" colspan="7"> <em><strong><?php echo $this->lang->line('lang_statistics_advertiser_rec_not');?></strong></em> </td></tr>
		<?php endif; ?>
    </tbody>
<?php if(count($stat_data['stat_list']) > 10)
{
?>
    <tfoot>
      <tr>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_horu');?></th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_impression');?> (<?php echo $stat_data['tot_val']['IMP']; ?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_clicks');?> (<?php echo $stat_data['tot_val']['CLK']; ?>)</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_conversions');?> (<?php echo $stat_data['tot_val']['CON']; ?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_ctr');?> (<?php echo number_format($stat_data['tot_val']['CTR'],2,".",","); ?>%)</th>
        </tr>
    </tfoot>
<?php }
?>
</table>

<form name="frmViewDateWise" method="post" id="frmViewDateWise" action="<?php echo site_url('admin/statistics_advertiser/view_hour_wise'); ?>">
	<input type="hidden" name="start_date" 	id="start_date" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" />
	<input type="hidden" name="sel_date" 	id="sel_date" />
	<input type="hidden" name="end_date" 	id="end_date" 	value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" />
	<input type="hidden" name="search_type" id="search_type" value="<?php echo $searchObj['search_type'];  ?>" />
	<input type="hidden" name="parent" 		id="parent"  value="ADV"/>
	<input type="hidden" name="ref_id" 		id="ref_id"  value="<?php echo $stat_adv_det[0]->client_id; ?>" />
	
</form>
<script>
	function view_reports_hour_wise(sel_date){
	
		document.getElementById('sel_date').value	=	sel_date;
		document.getElementById('frmViewDateWise').submit();
	}
</script>    
