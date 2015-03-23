 <!--[if IE 9]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie9.css"/>
<![endif]-->

<!--[if IE 8]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie8.css"/>
<![endif]-->

<!--[if IE 7]>
    <link rel="stylesheet" media="screen" href="<?php echo base_url();?>assets/css/ie7.css"/>
<![endif]-->
<?php
$tot_req = 0;
$tot_res = 0;
$tot_win = 0;
$tot_bid_price = 0;
$tot_win_price = 0;
$tot_admin_price = 0;
?>

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
<div id="statistics_title">
<h1 class="pageTitle"><?php echo "Smaato";?></h1>

<?php
if(!empty($stat_data['stat_list'])){
foreach($stat_data['stat_list'] as $temp){
	$data = $temp;
}


if($data['CLK'] > 0 OR $data['CON'] > 0 OR $data['SPEND'] > 0 OR $data['CTR'] > 0)
{
?>
	<a href="<?php echo site_url('admin/statistics_advertiser/export_advertisers_excel');?>" title="<?php echo $this->lang->line('lang_export_excel_ad_title');?>"><span class="export_excel_link"><?php echo $this->lang->line('label_export_excel'); ?></span></a>
	<?php } 
}
?>

</div>
<br/>		
<br/>	
		<form id="search_form" action="<?php echo site_url('admin/smaato_delivery_report/stats'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset style="padding:5px;">
                	<div style="width:100%;height:50px;padding-top:10px;">
						<div style="width:65%;height:50px;float:left;vertical-align:bottom;">
							<span style="margin:10px;" ><?php echo $this->lang->line('lang_statistics_advertiser_date');?></span>
							<?php
								$options_arr = array(
												"today"=>$this->lang->line('lang_statistics_today'),		
												"yesterday"=>$this->lang->line('lang_statistics_yesterday'),
												"thisweek"=>$this->lang->line('lang_statistics_this_week'),
												"last7days"=>$this->lang->line('lang_statistics_last_sev_day'),
												"thismonth"=>$this->lang->line('lang_statistics_this_month'),
												"lastmonth"=>$this->lang->line('lang_statistics_last_month'),
												"specific_date"=>$this->lang->line('lang_statistics_spec_date'),
												"all"=>$this->lang->line('lang_statistics_all_stats')
											);
							
								$sel_val = (set_value('search_field') != '')?set_value('search_field'):$search_type;
								echo form_dropdown('search_field', $options_arr,$sel_val,"onchange='show_date(this.value)' id='search_field' alt='".$this->lang->line('label_enter_advertiser')."'"); 
							?>
							<?php
								$searchObj = $this->session->userdata('statistics_search_arr');
							?>
							<span id="specificDataSec" style=" <?php echo ($sel_val=="specific_date")?"":"display:none"; ?>" >     
								<?php echo $this->lang->line('lang_statistics_advertiser_from_date');?>
								<input id="from_date" name="from_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" size="10" width="100" class="width100" /> 
								<?php echo $this->lang->line('lang_statistics_advertiser_to_date');?>
								<input id="to_date"  name="to_date" readonly="true" type="text" value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" size="10" width="100" class="width100" /> 
								<button style='margin-left:10px'><?php echo $this->lang->line('lang_statistics_advertiser_search');?></button>
							</span>
						</div>
							<?php if(count($advertiser_list) > 0 AND $advertiser_list != FALSE): ?>
							<div style="width:34%;height:50px;float:right;vertical-align:bottom;">
							<br/>
							<strong><?php echo $this->lang->line('lang_statistics_advertiser_from_date');?>:</strong> <?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>
							<?php echo $this->lang->line('lang_statistics_advertiser_to_date');?> :</strong> <?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>
							</div>
						   <?php endif; ?>	
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
			<th class="head1">Date</th>
            <th class="head0">Bid Request</th>
            <th class="head1">No Bid</th>
            <th class="head0">Bid Response</th>
            <th class="head1">Win Response</th>
            <th class="head0">eCPM</th>
            <th class="head1">Response Price</th>
            <th class="head0">Won Price</th>         
            <th class="head1">Admin Share</th>
            <th class="head0">Report</th>
        </tr>
    </thead>
    <colgroup>
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
    	if(!empty($stat_data)):
		foreach($stat_data as $stat):
		$totalearn = ($stat->win_price)?($stat->win_price)/1000:0;
		$totalimp  = ($stat->win_count)?$stat->win_count:0;
		$ecpm =0;		
		if($totalearn!=0 && $totalimp!=0)
			$ecpm =($totalearn/$totalimp)*1000; 
		?>
		<tr class="gradeX">
		  <td><?php echo date('d-M-Y', strtotime($stat->dbdate)); ?></td>
		  <td><?php echo $stat->req_count; ?></td>
		  <td><?php echo '0'; ?></td>
		  <td><?php echo ($stat->res_count)?$stat->res_count:0; ?></td>
		  <td><?php echo ($stat->win_count)?$stat->win_count:0; ?></td>
		  <td><?php echo '$'.$ecpm; ?></td>
		  <td><?php $bid_price=$this->mod_smaato_stats->get_bid_price($stat->dbdate); echo ($bid_price)?'$'.$bid_price/1000:'$0'; ?></td>
		  <td><?php echo ($stat->win_price)?'$'.$stat->win_price/1000:'$0'; ?></td>
		   <td><?php echo ($bid_price && $stat->win_price)?'$'.($bid_price-$stat->win_price)/1000:'$0'; ?></td>
		  <td><a href="<?php echo site_url('admin/smaato_delivery_report/detail_report/'.$stat->dbdate); ?>">Detail</a></td>
		</tr>
		<?php
		$tot_req += $stat->req_count;
		$tot_res += $stat->res_count;
		$tot_win += $stat->win_count;
		$tot_ecpm +=$ecpm;
		$tot_bid += $stat->bid_price;
		$tot_bid_price += $bid_price;
		$tot_win_price += $stat->win_price;
		$tot_admin_price += $bid_price-$stat->win_price;
		endforeach;
		else:
		?>	
		<tr><td align="center" colspan="7"> <em><strong><?php echo $this->lang->line('lang_statistics_advertiser_rec_not');?></strong></em> </td></tr>
		<?php endif; ?>
    </tbody>
    <?php if(!empty($stat_data)): ?>
	<tfoot>
        <tr>
            <th class="head1">Date</th>
            <th class="head0">Bid Request (<?php echo $tot_req; ?>)</th>
            <th class="head1">No Bid (0)</th>
            <th class="head0">Bid Response (<?php echo $tot_res; ?>)</th>
            <th class="head1">Won Response (<?php echo $tot_win; ?>)</th>
            <th class="head0">eCMP ($<?php echo ROUND($tot_ecpm,2); ?>)</th>
            <th class="head1">Response Price ($<?php echo ROUND($tot_bid_price/1000,2); ?>)</th>
            <th class="head0">Won Price ($<?php echo ROUND($tot_win_price/1000,2); ?>)</th>
            <th class="head1">Admin Price ($<?php echo ROUND($tot_admin_price/1000,2); ?>)</th>
            <th class="head0">&nbsp;</th>
        </tr>
    </tfoot>
	<?php endif; ?>
</table>
<form name="frmViewDateWise" method="post" id="frmViewDateWise" action="<?php echo site_url('admin/smaato_delivery_report/stats'); ?>">
	<input type="hidden" name="start_date" 	id="start_date" value="<?php echo date("m/d/Y",strtotime($searchObj['from_date']));  ?>" />
	<input type="hidden" name="end_date" 	id="end_date" 	value="<?php echo date("m/d/Y",strtotime($searchObj['to_date']));  ?>" />
	<input type="hidden" name="search_type" id="search_type" value="<?php echo $searchObj['search_type'];  ?>" />
	<input type="hidden" name="parent" 		id="parent"  />
	<input type="hidden" name="ref_id" 		id="ref_id"  />
	
</form>
<script>
	function view_reports_date_wise(ptype,refid){
	
		document.getElementById('parent').value	=	ptype;
		document.getElementById('ref_id').value	=	refid;
		document.getElementById('frmViewDateWise').submit();
	}
</script>    
