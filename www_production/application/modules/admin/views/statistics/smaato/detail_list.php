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
$tot_res =0;
$tot_win =0;
$tot_win_price =0;
$tot_rev =0;
$tot_bid_price=0;
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
<h1 class="pageTitle"><?php echo "Smaato - Detail Report";?></h1>

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
		<form id="search_form" action="<?php echo site_url('admin/stats_smaato/stats'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset style="padding:5px;">
                	<div style="width:100%;height:50px;padding-top:10px;">
						<div style="width:65%;height:50px;float:left;vertical-align:bottom;">
							<span style="margin:10px;" ><?php echo $this->lang->line('lang_statistics_advertiser_date');?></span>
							<?php echo date('d-M-Y', strtotime($date)); ?>
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
		
<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="adv_stat">
    <thead>
        <tr>
			<th class="head1">Advertiser</th>
			<th class="head0">Campaign</th>
			<th class="head1">Banner</th>
            <th class="head0">Bid Response</th>
            <th class="head1">Won Response</th>
            <th class="head0">Bid Price</th>
            <th class="head1">Response Price</th>
            <th class="head0">Won Price</th>
            <th class="head1">Admin share</th>
            
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
   </colgroup>
    <tbody>
    	<?php
    	if(!empty($stat_data)):
		foreach($stat_data as $stat):
		?>
		<tr class="gradeX">
		  <td><?php echo $stat->client_name; ?></td>
		  <td><?php echo $stat->camp_name; ?></td>
		  <td><?php echo $stat->ban_name; ?></td>
		  <td><?php echo ($stat->res_count)?$stat->res_count:'$0'; ?></td>
		  <td><?php echo ($stat->win_count)?$stat->win_count:'$0'; ?></td>
		  <td><?php echo ($stat->bid_price)?ROUND($stat->bid_price,2):'$0'; ?></td>
		  <td><?php echo ($stat->bid_rate && $stat->win_count)?'$'.($stat->bid_rate*$stat->win_count):'$0'; ?></td>
		  <td><?php echo ($stat->win_price)?'$'.$stat->win_price:'$0'; ?></td>
		  <td><?php echo ($stat->bid_rate && $stat->win_count && $stat->win_price)?'$'.($stat->bid_rate*$stat->win_count-$stat->win_price):'$0'; ?></td>
		</tr>
		<?php
		$tot_res += $stat->res_count;
		$tot_win += $stat->win_count;
		$tot_win_price += $stat->win_price;
		$tot_bid += $stat->bid_price;
		$tot_rev += ($stat->bid_rate*$stat->win_count)-$stat->win_price;
		$tot_bid_price += $stat->bid_rate*$stat->win_count;
		endforeach;
		else:
		?>	
		<tr><td align="center" colspan="7"> <em><strong><?php echo $this->lang->line('lang_statistics_advertiser_rec_not');?></strong></em> </td></tr>
		<?php endif; ?>
    </tbody>
    <?php if(!empty($stat_data)): ?>
	<tfoot>
        <tr>
            <th class="head1">Advertiser</th>
            <th class="head0">Camapign</th>
            <th class="head1">Banner</th>
            <th class="head0">Bid Response (<?php echo $tot_res; ?>)</th>
            <th class="head1">Won Response (<?php echo $tot_win; ?>)</th> 
            <th class="head0">Bid Price ($<?php echo ROUND($tot_bid,2); ?>)</th>
            <th class="head1">Response Price ($<?php echo ROUND($tot_bid_price,2); ?>)</th>
            <th class="head1">Won Price ($<?php echo ROUND($tot_win_price,2); ?>)</th>
            <th class="head0">Admin Share ($<?php echo ROUND($tot_rev,2); ?>)</th>
        </tr>
    </tfoot>
	<?php endif; ?>
</table>  
