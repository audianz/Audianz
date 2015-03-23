<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	jQuery(document).ready(function() {
			jQuery('#userlist').dataTable( {
			"sPaginationType": "full_numbers"
			});
			
		});
</script>
<script type="text/javascript">
        function delcamps()
        {
            var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
            if(camparr!='')
            {
                jConfirm('<?php echo $this->lang->line('label_camp_delete_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
                    if(r)
                    {
                        jQuery.post('<?php echo site_url('admin/inventory_campaigns/delete_campaign'); ?>', {'campaignarr[]': camparr}, function(response) {
                        location.reload();
                        });
                    }
                    else
                    {
                        document.getElementById('checkall').checked = false;
                        if(!jQuery(this).is(':checked')) {
		            jQuery('.sTable input[type=checkbox]').each(function(){
		                    jQuery(this).attr('checked',false);
		                    jQuery(this).parents('tr').removeClass('selected');
		            });
		    	}
                    }
                });
            }
            else
            {
                jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>');
            }
        }

        function pausecamps()
        {
			var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
			if(camparr!='')
			{
				jConfirm('<?php echo $this->lang->line('label_camp_pause_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
					if(r)
					{
						jQuery.post('<?php echo site_url('admin/inventory_campaigns/pause_campaign'); ?>', {'campaignarr[]': camparr}, function(response) {
						location.reload();

                                                });
                                        }
                                        else
                                        {
                                            document.getElementById('checkall').checked = false;
                                            if(!jQuery(this).is(':checked')) {
                                                    jQuery('.sTable input[type=checkbox]').each(function(){
                                                            jQuery(this).attr('checked',false);
                                                            jQuery(this).parents('tr').removeClass('selected');
                                                    });
                                            }
                                        }
                                });

			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>');
			}
			
        }

        function runcamps()
        {
			var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
			if(camparr!='')
			{
                                jConfirm('<?php echo $this->lang->line('label_camp_run_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
					if(r)
					{
						jQuery.post('<?php echo site_url('admin/inventory_campaigns/run_campaign'); ?>',{'campaignarr[]': camparr}, function(response){
						//alert(response);
						location.reload();

                                                });
                                        }
                                        else
                                        {
                                            document.getElementById('checkall').checked = false;
                                            if(!jQuery(this).is(':checked')) {
                                                    jQuery('.sTable input[type=checkbox]').each(function(){
                                                            jQuery(this).attr('checked',false);
                                                            jQuery(this).parents('tr').removeClass('selected');
                                                    });
                                            }
                                        }
                                });				
			}
			else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>');
			}	  
        }

        function isDelsingle(camp_id)
        {
		
                jConfirm('<?php echo $this->lang->line("label_camp_delete_confirm"); ?>','<?php echo $this->lang->line("label_inventory_campaign_page_title"); ?>',function(r){
                if(r)
                {
                    document.location.href = '<?php echo site_url('admin/inventory_campaigns/delete_campaign');?>/'+camp_id;
                }
            });
        }
	function isDelsingle2(camp_id, sel_adv)
        {
		
                jConfirm('<?php echo $this->lang->line("label_camp_delete_confirm"); ?>','<?php echo $this->lang->line("label_inventory_campaign_page_title"); ?>',function(r){
                if(r)
                {
                    document.location.href = '<?php echo site_url('admin/inventory_campaigns/delete_campaign');?>/'+camp_id+'/'+sel_adv;
                }
            });
        }
</script>
<?php if($this->session->flashdata('form_add_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_add_success'); ?></p></div>
<?php endif; 

if($this->session->flashdata('form_edit_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_edit_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('form_delete_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('delete_campaign') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php	    
endif;

if($this->session->flashdata('form_target_success') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_target_success'); ?></p></div>
<?php	    
endif;

if($this->session->flashdata('pause_campaign') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('pause_campaign'); ?></p></div>
<?php 
endif;

if($this->session->flashdata('block_run_campaign') != ""): ?>
<div class="notification msginfo"> <a class="close"></a> <p><?php echo $this->session->flashdata('block_run_campaign'); ?></p></div>
<?php
elseif($this->session->flashdata('complete_campaign') != ""): ?>
<div class="notification msgerror"> <a class="close"></a> <p><?php echo $this->session->flashdata('complete_campaign'); ?></p></div>
<?php
else:
if($this->session->flashdata('run_campaign') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('run_campaign'); ?></p></div>
<?php
endif;
endif;

if($this->session->flashdata('camp_error') != ""): ?> 
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('camp_error'); ?></p></div>
<?php
endif;
if($this->session->flashdata('budget_completed') != ""): ?>
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('budget_completed'); ?></p></div>
<?php
endif;

?>

<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_campaign_page_title'); ?></h1>
<?php
if($sel_adv != '0' || $sel_adv != '')
{
?>
<a href="<?php echo site_url('admin/inventory_campaigns/add_campaign/'.$sel_adv)."" ?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_add_campaign'); ?></a>
<?php
}
else
{
?>
<a href="<?php echo site_url('admin/inventory_campaigns/add_campaign')."" ?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_add_campaign'); ?></a>
<?php
}
?>
<?php if(!empty($campaign_list)): ?>

<ul class="submenu">
	<?php if($sel_adv!=''): ?>
            <li <?php if($this->uri->segment(4)=='' || $this->uri->segment(4)=='all'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing_adv/all/'.$sel_adv); ?>"><?php echo $this->lang->line('label_all'); ?> (<?php echo $tot_data; ?>)</a></li>
            <li <?php if($this->uri->segment(4)=='active'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing_adv/active/'.$sel_adv); ?>"><?php echo $this->lang->line('label_active'); ?> (<?php echo $active_data; ?>)</a></li>
            <li <?php if($this->uri->segment(4)=='inactive'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing_adv/inactive/'.$sel_adv); ?>"><?php echo $this->lang->line('label_inactive'); ?> (<?php echo $inactive_data; ?>)</a></li>
	    <li <?php if($this->uri->segment(4)=='inactive'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing_adv/awaiting/'.$sel_adv); ?>"><?php echo $this->lang->line('label_awaiting'); ?> (<?php echo $awaiting_data; ?>)</a></li>
	     <li <?php if($this->uri->segment(4)=='completed'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing_adv/completed/'.$sel_adv); ?>"><?php echo $this->lang->line('label_completed'); ?> (<?php echo $completed_data; ?>)</a></li>
        <?php else: ?>
            <li <?php if($this->uri->segment(4)=='' || $this->uri->segment(4)=='all'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns'); ?>"><?php echo $this->lang->line('label_all'); ?> (<?php echo $tot_data; ?>)</a></li>
            <li <?php if($this->uri->segment(4)=='active'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing/active'); ?>"><?php echo $this->lang->line('label_active'); ?> (<?php echo $active_data; ?>)</a></li>
            <li <?php if($this->uri->segment(4)=='inactive'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing/inactive'); ?>"><?php echo $this->lang->line('label_inactive'); ?> (<?php echo $inactive_data; ?>)</a></li>
            <li <?php if($this->uri->segment(4)=='awaiting'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing/awaiting'); ?>"><?php echo $this->lang->line('label_awaiting'); ?> (<?php echo $awaiting_data; ?>)</a></li>
	   <li <?php if($this->uri->segment(4)=='completed'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_campaigns/listing/completed'); ?>"><?php echo $this->lang->line('label_completed'); ?> (<?php echo $completed_data; ?>)</a></li>
        <?php endif; ?>
</ul>
<?php endif; ?>
<br />
 <br />
<?php if(!empty($campaign_list)): ?>
<div class="sTableOptions">
	<?php echo $this->pagination->create_links(); ?>
	
    	<a href="javascript:void(0);" onclick="pausecamps();" class="button pause"><span><?php echo $this->lang->line('label_pause_campaign'); ?></span></a>
	<a href="javascript:void(0);" onclick="runcamps();" class="button run"><span><?php echo $this->lang->line('label_run_campaign'); ?></span></a>
	<a href="javascript:void(0);" class="button delete" onclick="delcamps();"><span><?php echo $this->lang->line('label_delete_campaign'); ?></span></a>
</div><!--sTableOptions-->
	<?php endif; ?>

<form id="frmCampaignList" action="<?php echo site_url('admin/inventory_campaigns/process'); ?>" method="post" >



	<table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">

	   <thead>
               <tr>
					<th class="head1"><input type="checkbox" class="checkall" id="checkall" style="margin-left: 14px;"/></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_campaigns');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_inventory_revenue_type');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_targetting_banners');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_status');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_action');?></th>
                </tr>
       </thead>
            
		<colgroup>
			<col class="con0" width="5%" />
			<col class="con1" width="20%" />
			<col class="con0" width="10%" />
			<col class="con1" width="15%" />
			<col class="con0" width="15%" />
			<col class="con1" width="15%" />
		</colgroup>
		<?php if(!empty($campaign_list)): ?>
		<?php foreach($campaign_list as $campaign): ?>
		<?php $client	=($sel_adv ==0)?$campaign->clientid:$sel_adv; ?>
		<tr>
			<td align="center"><input type="checkbox" id="<?php echo view_text($campaign->campaignid); ?>" /></td>
			<td><?php echo view_text($campaign->campaignname); ?></td>
			<td align=""><?php echo $this->mod_campaign->get_revenue_type($campaign->revenue_type); ?></td>
			<td align="">
			<?php if($campaign->rtb==1): ?>
						<a href="<?php echo site_url('admin/inventory_campaigns/rtb_targeting/'.$campaign->campaignid.'/'.$campaign->rtb); ?>"> <?php echo "RTB Targeting"; ?> </a> -
					<?php else: ?>
						<a href="<?php echo site_url('admin/inventory_campaigns/targeting/'.$campaign->campaignid); ?>"> <?php echo "Targeting"; ?> </a> -
					<?php endif; ?>
				 
				<a href="<?php echo site_url("admin/inventory_banners/listing_camp/all/".$campaign->campaignid.'/'.$client); ?>"> <?php echo $this->lang->line('label_list'); ?></a>
			</td>
			<td align=""><?php echo ucfirst($this->mod_campaign->get_campaign_status($campaign->status)); ?></td>
			<td align="">
                            <a href="<?php echo site_url('admin/inventory_campaigns/edit_campaign/'.$campaign->campaignid.'/'.$client); ?>"><?php echo $this->lang->line('label_edit'); ?></a> &nbsp; 
			<?php 
			if($sel_adv == 0 || $sel_adv == '')
			{
			?> 
                            <a href="javascript:isDelsingle(<?php echo $campaign->campaignid; ?>)"><?php echo $this->lang->line("label_delete"); ?></a>
			<?php
			}
			else
			{
			?>
			   <a href="javascript:isDelsingle2(<?php echo $campaign->campaignid;?>,<?php echo $sel_adv;?>)"><?php echo $this->lang->line("label_delete"); ?></a>
			<?php
			}
			?>	
           </td>
		</tr>

                <?php endforeach; 
                endif; ?>

	</table>
 
</form>





