<link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/style.css" />
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 
 
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
<div>
<h1 class="pageTitle"><?php echo $this->lang->line('lang_statistics_store_report');?></h1>
<a style="text-decoration:none; margin-left:90%;" href="<?php echo site_url('advertiser/store_report/export_stores_excel');?>" title="<?php echo "Click here to export storewise report";?>"><span style="background: url(<?php echo $this->config->item('base_url');?>assets/images/icons/export_excel.png);background-repeat:no-repeat;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('label_export_excel'); ?></span></a>
</div>
<form id="search_form" action="<?php echo site_url("advertiser/store_report"); ?>" method="post">
        
        	<div class="form_default">
                <fieldset style="padding:5px;">
                	<div style="width:100%;height:50px;padding-top:10px;">
						<div style="width:70%;height:50px;float:left;vertical-align:bottom;">
							<span style="margin:10px;" ><?php echo $this->lang->line('lang_statistics_filter_option_s');?></span>
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
						  <?php if(count($stat_data['stat_list']) > 10): ?>
						<div style="width:30%;height:50px;float:right;vertical-align:bottom;">
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_from_date');?> :</strong> <?php echo date("d-m-Y",strtotime($searchObj['from_date']));  ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>
							<?php echo $this->lang->line('lang_statistics_advertiser_to_date');?> :</strong> <?php echo date("d-m-Y",strtotime($searchObj['to_date']));  ?>
							</div>
						<?php  endif; ?>	
					</div>
				</fieldset>
            </div><!--form-->
        </form>
	   
<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="adv_stat">
    <thead>
        <tr>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_date');?></th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_store_name');?></th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_store_city');?></th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_impression');?> (<?php echo $stat_data['tot_val']['IMP']; ?>)</th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_clicks');?> (<?php echo $stat_data['tot_val']['CLK']; ?>)</th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_landing_page');?>(<?php echo $stat_data['tot_val']['LAND']; ?>)</th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_call');?> (<?php echo $stat_data['tot_val']['CALL']; ?>)</th>
		    <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_web');?> (<?php echo $stat_data['tot_val']['WEB']; ?>)</th>
		    <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_map');?> (<?php echo $stat_data['tot_val']['MAP']; ?>)</th>
            
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
    </colgroup>
    <tbody>
     	<?php
   
    	if(count($stat_data['stat_list']) > 0):
		foreach($stat_data['stat_list'] as $objStat):
		
		?>
 		<tr class="gradeX">
		  <td><?php echo date("M d, Y",strtotime($objStat['date'])); ?></td>
		  <td style="text-align:left"><?php echo $objStat['POI_NAME']; ?></td>
		  <td style="text-align:left"><?php echo $objStat['CITY']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['IMP']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['CLK']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['LAND']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['CALL']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['WEB']; ?></td>
		  <td style="text-align:center"><?php echo $objStat['MAP']; ?></td>
		  
		</tr>
		<?php
		endforeach;
		else:
		?>	
		<tr><td align="center" colspan="7"> <em><strong><?php echo $this->lang->line('lang_statistics_advertiser_rec_not');?></strong></em> </td></tr>
		<?php endif; ?>
    </tbody>
     <?php if(count($stat_data['stat_list']) > 10): ?>
	<tfoot>
        <tr>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_date');?></th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_store_name');?></th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_store_city');?></th>
          	<th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_impression');?>(<?php echo $stat_data['tot_val']['IMP']; ?>)</th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_clicks');?>(<?php echo $stat_data['tot_val']['CLK']; ?>)</th>
			<th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_landing_page');?>(<?php echo $stat_data['tot_val']['LAND']; ?>)</th>
			<th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_call');?> (<?php echo $stat_data['tot_val']['CALL']; ?>)</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_advertiser_web');?> (<?php echo $stat_data['tot_val']['WEB']; ?>)</th>
            <th class="head0"><?php echo $this->lang->line('lang_statistics_advertiser_map');?> (<?php echo $stat_data['tot_val']['MAP']; ?>)</th>
         </tr>
    </tfoot>
	<?php endif; ?>
</table>
		
<form name="frmViewDateWise" method="post" id="frmViewDateWise" action="<?php echo site_url('admin/statistics_advertiser/view_hour_wise'); ?>">
	<input type="hidden" name="start_date" 	id="start_date" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" />
	<input type="hidden" name="sel_date" 	id="sel_date" />
	<input type="hidden" name="end_date" 	id="end_date" 	value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" />
	<input type="hidden" name="search_type" id="search_type" value="<?php echo $searchObj['search_type'];  ?>" />
	<input type="hidden" name="ref_id" 		id="ref_id"  value="<?php echo $searchObj['sel_advertiser_id']; ?>" />
	
</form>
<script>
	function view_reports_hour_wise(sel_date){
	
		document.getElementById('sel_date').value	=	sel_date;
		document.getElementById('frmViewDateWise').submit();
	}
</script>
