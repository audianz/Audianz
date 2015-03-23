<script type="text/javascript">

jQuery(document).ready(function() {

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
		
		jQuery.post('<?php echo site_url('admin/statistics_publisher/view_more_details'); ?>', {'account_id': adv_id}, function(response) {
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
	
<div id="statistics_title">	
<h1 class="pageTitle"><?php echo $this->lang->line('lang_statistics_publisher_statistics');?></h1>
<?php
$data=$stat_data['stat_list'];
if(!empty($data))
{
	if($stat_data['tot_val']['CLK'] > 0 OR $stat_data['tot_val']['CON'] > 0 OR $stat_data['tot_val']['SPEND'] > 0 OR $stat_data['tot_val']['CTR'] > 0)
	{
	?>
	<a href="<?php echo site_url('admin/statistics_publisher/export_publishers_excel');?>" title="<?php echo $this->lang->line('lang_export_excel_pub_title');?>"><span class="export_excel_link"><?php echo $this->lang->line('label_export_excel'); ?></span></a>
	<?php 
	}
} ?>
</div>
<br/>		
<br/>	  
<form id="search_form" action="<?php echo site_url("admin/statistics_publisher/view"); ?>" method="post">
        
        	<div class="form_default">
                <fieldset style="padding:5px;">
                	<div style="width:100%;height:50px;padding-top:10px;">
						<div style="width:65%;height:50px;float:left;vertical-align:bottom;">
							<span style="margin:10px;" ><?php echo $this->lang->line('lang_statistics_global_date'); ?></span>
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
							
								$sel_val = (set_value('search_field') != '')?set_value('search_field'):$this->input->post('search_field');
								echo form_dropdown('search_field', $options_arr,$sel_val,"onchange='show_date(this.value)' id='search_field' alt='".$this->lang->line('label_enter_advertiser')."'"); 
							?>
							<?php
								$searchObj = $this->session->userdata('statistics_search_arr');
							?>
							<span id="specificDataSec" style=" <?php echo ($sel_val=="specific_date")?"":"display:none"; ?>" >     
								<?php echo $this->lang->line('lang_statistics_publisher_from_date');?>
								<input id="from_date" name="from_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" size="10" width="100" class="width100" /> 
								<?php echo $this->lang->line('lang_statistics_publisher_to_date');?>
								<input id="to_date"  name="to_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" size="10" width="100" class="width100" /> 
								<button style='margin-left:10px'><?php echo $this->lang->line('label_search16');?></button>
							</span>
						</div>
						<div style="width:34%;height:50px;float:right;vertical-align:bottom;">
							<?php if(count($publisher_list) > 0): ?>
							<strong><?php echo $this->lang->line('lang_statistics_publisher_from_date');?>:</strong> <?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><?php echo $this->lang->line('lang_statistics_publisher_to_date');?> :</strong> 
							<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>
							<?php endif; ?>
							</div>
					</div>
				</fieldset>
            </div><!--form-->
        </form>
		 <?php
			$tot_val	=	$stat_data['tot_val'];
		?>
  <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="adv_stat">
    <thead>
        <tr>
			<th class="head0">&nbsp;</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_site');?></th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_impression');?> (<?php echo $stat_data['tot_val']['IMP'];?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_clicks');?> (<?php echo $stat_data['tot_val']['CLK'];?>)</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_conversions');?> (<?php echo $stat_data['tot_val']['CON'];?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_call');?> (<?php echo $stat_data['tot_val']['CALL'];?>)</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_web');?> (<?php echo $stat_data['tot_val']['WEB'];?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_map');?> (<?php echo $stat_data['tot_val']['MAP'];?>)</th>
            
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_ctr');?> (<?php echo number_format($stat_data['tot_val']['CTR'],2,".",","); ?>%)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_revenue');?> ($<?php echo number_format($stat_data['tot_val']['SPEND'],2,".",","); ?>)</th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_share');?> ($<?php echo number_format($stat_data['tot_val']['PUBSHARE'],2,".",","); ?>)</th>
        </tr>
    </thead>
    <colgroup>
        <col class="con0" />
        <col class="con1" />
        <col class="con0" />
    	<col class="con1" />
    	<col class="con0" />
    	<col class="con1" />
		<col class="con0" />
		<col class="con1" />
		<col class="con0" />
		<col class="con1" />
		<col class="con0" />
    </colgroup>
    <tbody>
    <?php
		
		$stat_list	=	$stat_data['stat_list'];
	
    	if(count($publisher_list) > 0):
		foreach($publisher_list as $adv):
		?>
		<tr class="gradeX">
		  <td id="<?php echo $adv->accountid; ?>" align="center"><?php echo (isset($stat_list[$adv->accountid]))?"<a href='javascript:open_toggle(".$adv->accountid.");'><img id='test".$adv->accountid."' src='".base_url("assets/images/icons/toggle_up.jpeg")."'></a>":"&nbsp;"; ?></td>
		  <td><!--<a href="javascript: view_reports_date_wise('PUB','<?php echo $adv->accountid; ?>')" ><?php echo $adv->websitename; ?></a>
-->		  <?php if((isset($stat_list[$adv->accountid]['IMP']) AND $stat_list[$adv->accountid]['IMP'] > 0 ) || (isset($stat_list[$adv->accountid]['CLK']) AND $stat_list[$adv->accountid]['CLK'] > 0) || (isset($stat_list[$adv->accountid]['CON']) AND $stat_list[$adv->accountid]['CON'] > 0 ) ): ?>
													<a href="javascript: view_reports_date_wise('PUB','<?php echo $adv->accountid; ?>')" ><?php echo view_text($adv->websitename); ?></a>
		  <?php else: ?>
			  <?php echo view_text($adv->websitename); ?>
		  <?php endif; ?>
		  </td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['IMP']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['CLK']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['CON']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['CALL']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['WEB']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['MAP']:'0'; ?></td>
		  <td><?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['CTR']:"0.00";?>%</td>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['SPEND']:'0.00';?></td>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['PUBSHARE']:'0.00';?></td>
		</tr>
		<?php 
		if(isset($stat_list[$adv->accountid])):
		?>
		<tr style="display:none;" id="child_row<?php echo $adv->accountid; ?>" >
		<td colspan="8">
		<div style="text-align:center;" id="child_conetnt_<?php echo $adv->accountid; ?>">
			<img src="<?php echo base_url("assets/images/loaders/loader6.gif"); ?>" alt="" />
			
		</div>
		</td>
		</tr>
		<?php endif;
		endforeach;
		else:
		?>
		<tr><td align="center" colspan="7"><strong><?php echo $this->lang->line('lang_statistics_publisher_not_found'); ?></strong></td></tr>
		<?php endif; ?>
    </tbody>
<?php
if(count($data)>10)
{
?>
    <tfoot>
        <tr>
            <th class="head0">&nbsp;</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_site');?></th>
          	<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_impression');?> (<?php echo $tot_val['IMP']; ?>) </th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_clicks');?> (<?php echo $tot_val['CLK']; ?>) </th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_conversions');?>(<?php echo $tot_val['CON']; ?>) </th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_call');?>(<?php echo $tot_val['CALL']; ?>) </th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_web');?>(<?php echo $tot_val['WEB']; ?>) </th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_map');?>(<?php echo $tot_val['MAP']; ?>) </th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_ctr');?>(<?php echo $tot_val['CTR']; ?> %) </th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_revenue');?>($<?php echo $tot_val['SPEND']; ?>) </th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_share');?>($<?php echo $tot_val['PUBSHARE']; ?>) </th>
        </tr>
    </tfoot>
<?php
}
?>
</table>    

<form name="frmViewDateWise" method="post" id="frmViewDateWise" action="<?php echo site_url('admin/statistics_publisher/view_date_wise'); ?>">
	<input type="hidden" name="start_date" 	id="start_date" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" />
	<input type="hidden" name="end_date" 	id="end_date" 	value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" />
	<input type="hidden" name="search_type" id="search_type" value="<?php echo $searchObj['search_type'];  ?>" />
	<input type="hidden" name="parent" 		id="parent"  />
	<input type="hidden" name="ref_id" 		id="ref_id"  />
	<input type="hidden" name="zone_id" 		id="zone_id"  />
	
</form>
<script>
	function view_reports_date_wise(ptype,refid,zoneid){
	
		document.getElementById('parent').value	=	ptype;
		document.getElementById('ref_id').value	=	refid;
		document.getElementById('zone_id').value=	zoneid;
		document.getElementById('frmViewDateWise').submit();
	}
</script>    
