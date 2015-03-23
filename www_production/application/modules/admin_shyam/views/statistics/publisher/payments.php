<script type="text/javascript">
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
		
		jQuery.post('<?php echo site_url("admin/payment_history/view_more_details"); ?>', {'account_id': adv_id}, function(response) {
			//document.getElementById("child_conetnt_"+adv_id).innerHTML = response;
			
			jQuery("#child_conetnt_"+adv_id).html(response);			
			jQuery("#child_conetnt_"+adv_id).show();			
	    });
		
	}	  
}
</script>

<div id="statistics_title">	
<h1 class="pageTitle"><?php echo $this->lang->line('label_payment_history_data');?></h1>
<?php
$data=$stat_data['stat_list'];
if(!empty($data))
{
?>
<a href="<?php echo site_url('admin/payment_history/export_payment_history');?>" title="<?php echo $this->lang->line('lang_export_excel_pub_title');?>"><span class="export_excel_link"><?php echo $this->lang->line('label_export_excel'); ?></span></a>
<?php } ?>
</div>
<br/>		
<br/>	  
  <?php $tot = $stat_data['tot_val']; ?>
  <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="adv_stat">
    <thead>
        <tr>
	    <th class="head0">&nbsp;</th>
            <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_site');?></th>
            
            <th class="head0"><?php echo $this->lang->line('lang_statistics_publisher_revenue');?> ($<?php echo $tot['SPEND']; ?>)</th>
	    <th class="head1"><?php echo $this->lang->line('lang_statistics_publisher_share');?> ($<?php echo $tot['PUBSHARE']; ?>)</th>
	    <th class="head0"><?php  echo $this->lang->line('lang_paid_by_admin');?> ($<?php echo number_format($tot['PAID'],2); ?>)</th>
	    <?php $unpaid = $tot['PUBSHARE']-$tot['PAID']; ?>
	    <th class="head1"><?php  echo $this->lang->line('lang_publisher_option_unpaid_earnings');?> ($<?php echo number_format($unpaid,2); ?>)</th>
        </tr>
    </thead>
    <colgroup>
        <col class="con0" />
        <col class="con1" />
        <col class="con0" />
    	<col class="con1" />
    	<col class="con0" />
    	<col class="con1" />	
    </colgroup>
    <tbody>
    <?php
		
	$stat_list = $stat_data['stat_list'];
	if(count($publisher_list) > 0):
		foreach($publisher_list as $adv):
		?>
		<tr class="gradeX">
		  <td id="<?php echo $adv->accountid; ?>" align="center"><?php echo (isset($stat_list[$adv->accountid]))?"<a href='javascript:open_toggle(".$adv->accountid.");'><img id='test".$adv->accountid."' src='".base_url("assets/images/icons/toggle_up.jpeg")."'></a>":"&nbsp;"; ?></td>
		  <td><?php if((isset($stat_list[$adv->accountid]['IMP']) AND $stat_list[$adv->accountid]['IMP'] > 0 ) || (isset($stat_list[$adv->accountid]['CLK']) AND $stat_list[$adv->accountid]['CLK'] > 0) || (isset($stat_list[$adv->accountid]['CON']) AND $stat_list[$adv->accountid]['CON'] > 0 ) ): ?>
													<a href="javascript: view_reports_date_wise('PUB','<?php echo $adv->accountid; ?>')" ><?php echo view_text($adv->websitename); ?></a>
		  <?php else: ?>
			  <?php echo view_text($adv->websitename); ?>
		  <?php endif; ?>
		  </td>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['SPEND']:'0.00';?></td>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['PUBSHARE']:'0.00';?></td>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$stat_list[$adv->accountid]['PAID']:'0.00';?></td>
		  <?php if(isset($stat_list[$adv->accountid])):
		  $unpaidpub = $stat_list[$adv->accountid]['PUBSHARE']-$stat_list[$adv->accountid]['PAID']; 
		  endif; ?>
		  <td>$<?php echo (isset($stat_list[$adv->accountid]))?$unpaidpub:'0.00';?></td>
		</tr>
		<?php 
		if(isset($stat_list[$adv->accountid])):
		?>
		<tr style="display:none;" id="child_row<?php echo $adv->accountid; ?>" >
		<td colspan="8">
		<div style="text-align:center;" id="child_conetnt_<?php echo $adv->accountid; ?>">
			<img src="<?php echo base_url('assets/images/loaders/loader6.gif'); ?>" alt='' />
			
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
</table>    
  
