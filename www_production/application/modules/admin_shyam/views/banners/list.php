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
	function pause_banners()
	{
		var bannerlist = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
		if(bannerlist!='')
		{
			jConfirm('<?php echo $this->lang->line('label_banner_pause_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                    jQuery.post('<?php echo site_url('admin/inventory_banners/pause_banner'); ?>', {'bannerarr[]': bannerlist}, function(response) {
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
			jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
		}
	}
	function run_banners()
	{
		var bannerarr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
		if(bannerarr!='')
		{
			jConfirm('<?php echo $this->lang->line('label_banner_run_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                    jQuery.post('<?php echo site_url('admin/inventory_banners/run_banner'); ?>', {'bannerarr[]': bannerarr}, function(response) {
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
			jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
		}
	}
	function delete_banners()
	{
		var bannerarr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
		if(bannerarr!='')
		{
			
                       jConfirm('<?php echo $this->lang->line('label_banner_delete_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                    jQuery.post('<?php echo site_url('admin/inventory_banners/delete_banner'); ?>', {'bannerarr[]': bannerarr}, function(response) {
					location.reload();

				});
                            }
                            else
                            {
                                document.getElementById('checkall').checked = false;
                            }
                    });
		   
		}
                else
                {
                    jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
                }
	}

        function isDelsingle(banner_id)
        {
                jConfirm('<?php echo $this->lang->line("label_banner_delete_confirm"); ?>','<?php echo $this->lang->line("label_inventory_banner_page_title"); ?>',function(r){
                if(r)
                {
                    document.location.href = '<?php echo site_url('admin/inventory_banners/delete_banner');?>/'+banner_id;
                }
            });
        }

	function isDelsingle1(banner_id,sel_camp)
        {
                jConfirm('<?php echo $this->lang->line("label_banner_delete_confirm"); ?>','<?php echo $this->lang->line("label_inventory_banner_page_title"); ?>',function(r){
                if(r)
                {
                    document.location.href = '<?php echo site_url('admin/inventory_banners/delete_banner');?>/'+banner_id+'/'+sel_camp;
                }
            });
        }

	function isDelsingle2(banner_id,sel_camp,sel_adv)
        {
                jConfirm('<?php echo $this->lang->line("label_banner_delete_confirm"); ?>','<?php echo $this->lang->line("label_inventory_banner_page_title"); ?>',function(r){
                if(r)
                {
                    document.location.href = '<?php echo site_url('admin/inventory_banners/delete_banner');?>/'+banner_id+'/'+sel_camp+'/'+sel_adv;
                }
            });
        }
</script>
<?php if($this->session->flashdata('banner_add_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_add_success'); ?></p></div>
<?php endif; 
 

if($this->session->flashdata('banner_edit_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_edit_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('banner_delete_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_delete_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('delete_campaign') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php	    
endif;

if($this->session->flashdata('pause_banner') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('pause_banner'); ?></p></div>
<?php 
endif;

if($this->session->flashdata('run_banner') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('run_banner'); ?></p></div>
<?php
endif;

if($this->session->flashdata('run_banner_err') != ""): ?>
    <div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('run_banner_err')." - " ?>
<?php //$err_data=$this->session->userdata('run_banner_err_data');
?></p></div>
<?php endif;
if($this->session->flashdata('banner_error') != ""): ?> 
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('banner_error'); ?></p></div>
<?php
endif;
?>


<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_banner_page_title'); ?></h1>
<?php if($sel_camp!='' || $sel_camp!='0'){ ?>
<a href="<?php echo site_url("admin/inventory_banners/add_banner/".$sel_camp.'/'.$sel_adv); ?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_add_banner'); ?></a>
<?php }else{?>
<a href="<?php echo site_url('admin/inventory_banners/add_banner'); ?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_add_banner'); ?></a>
<?php }?>
<?php 
	if(!empty($banner_list)){ ?>
<ul class="submenu">
    <?php if($sel_camp!=''){ ?>
    <li <?php if($this->uri->segment(4)=='' || $this->uri->segment(4)=='all'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing_camp/all/'.$sel_camp); ?>"><?php echo $this->lang->line('label_all'); ?> (<?php echo $tot_data; ?>)</a></li>
    <li <?php if($this->uri->segment(4)=='active'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing_camp/active/'.$sel_camp); ?>"><?php echo $this->lang->line('label_active'); ?> (<?php echo $active_data; ?>)</a></li>
    <li <?php if($this->uri->segment(4)=='inactive'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing_camp/inactive/'.$sel_camp); ?>"><?php echo $this->lang->line('label_inactive'); ?> (<?php echo $inactive_data; ?>)</a></li>
    <?php } else { ?>
    <li <?php if($this->uri->segment(4)=='' || $this->uri->segment(4)=='all'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing/all'); ?>"><?php echo $this->lang->line('label_all'); ?> (<?php echo $tot_data; ?>)</a></li>
    <li <?php if($this->uri->segment(4)=='active'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing/active'); ?>"><?php echo $this->lang->line('label_active'); ?> (<?php echo $active_data; ?>)</a></li>
    <li <?php if($this->uri->segment(4)=='inactive'){ ?>class="current"<?php } ?>><a href="<?php echo site_url('admin/inventory_banners/listing/inactive'); ?>"><?php echo $this->lang->line('label_inactive'); ?> (<?php echo $inactive_data; ?>)</a></li>
    <?php } ?>    
</ul>
<?php } ?>
	<br />
	<br />
	<?php 
	if(!empty($banner_list)){ ?>
	<div class="sTableOptions">
		<?php echo $this->pagination->create_links(); ?>
		<a href="javascript:void(0);" onclick="pause_banners();" class="button pause"><span><?php echo $this->lang->line('label_pause_banner'); ?></span></a>
		<a href="javascript:void(0);" onclick="run_banners();" class="button run"><span><?php echo $this->lang->line('label_run_banner'); ?></span></a>
		<a href="javascript:void(0);" class="button delete" onclick="delete_banners();"><span><?php echo $this->lang->line('label_delete_banner'); ?></span></a>

	</div><!--sTableOptions-->
	<?php } ?>

	<table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">

		  <thead>
                <tr>
					<th class="head1"><input type="checkbox" class="checkall" id="checkall" style="margin-left: 8px;"/></th>
                    <th class="head0"><?php echo $this->lang->line('label_banner_name');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_type');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_impressions');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_clicks');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_conversions');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_ctr');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_spend');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_status');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_action');?></th>


              
				</tr>
            </thead>
			<colgroup>
				<col class="con0" width="5%" />
				<col class="con1" width="15%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
				<col class="con0" width="10%" />
				<col class="con1" width="10%" />
			</colgroup>
			<tbody>
							
				<?php if($banner_list!=FALSE): foreach($banner_list as $banner): ?>

				<tr>
				<?php
				$clientid = $banner->clientid;
				$adv_budget=$this->mod_campaign->check_advertiser_balance($clientid);
				if($adv_budget == FALSE || $adv_budget== '0')
				{
					$adv_budget=0;
					?>
					<td align="center"><input disabled="disabled" type="checkbox" id="<?php echo $banner->bannerid; ?>" /><input type="hidden" name="bid" disabled="disabled" value="<?php echo $banner->bannerid; ?>" /></td>
				<?php 
				}
				else
				{
					$adv_budget=$adv_budget[0]->accbalance;
					if($adv_budget>0)
					{
						if($banner->adminstatus==0)				
						{				
						?>
						<td align="center"><input type="checkbox" id="<?php echo $banner->bannerid; ?>" /><input type="hidden" name="bid" value="<?php echo $banner->bannerid; ?>" /></td>
						<?php
						}
						else
						{
						?>
						<td align="center"><input disabled="disabled" type="checkbox" id="<?php echo $banner->bannerid; ?>" /><input type="hidden" name="bid" disabled="disabled" value="<?php echo $banner->bannerid; ?>" /></td>
						<?php 
						}
					}
					else
					{
					?>
					<td align="center"><input disabled="disabled" type="checkbox" id="<?php echo $banner->bannerid; ?>" /><input type="hidden" name="bid" disabled="disabled" value="<?php echo $banner->bannerid; ?>" /></td>
					<?php 
					}
				}
				?>
				<td><?php echo view_text($banner->description); ?></td>
				<td><?php echo view_text($this->mod_banner->get_revenue_type($banner->revenue_type)); ?></td>
				<td align=""><?php echo view_text($stat_data[$banner->campaignid][$banner->bannerid]["IMP"]); ?></td>
				<td align=""><?php echo view_text($stat_data[$banner->campaignid][$banner->bannerid]["CLK"]); ?></td>
				<td align=""><?php echo view_text($stat_data[$banner->campaignid][$banner->bannerid]["CON"]); ?></td>
				<td align=""><?php echo view_text($stat_data[$banner->campaignid][$banner->bannerid]["CTR"]); ?> %</td>
				<td align="">$<?php echo view_text($stat_data[$banner->campaignid][$banner->bannerid]["SPEND"]); ?></td>
				<?php if($banner->banstatus==0 && $adv_budget>0 && $banner->adminstatus==0): ?>
				<td align=""><?php echo $this->lang->line('label_running'); ?></td>
				<?php else: ?>
				<td align=""><?php echo $this->lang->line('label_paused'); ?></td>
				<?php endif; ?>
				<td align="">
				<?php if(($sel_camp!='' || $sel_camp!=0) && ($sel_adv!='' || $sel_adv!=0)){ ?>
				<a href="<?php echo site_url('admin/inventory_banners/edit_banner/'.$banner->bannerid.'/'.$sel_camp.'/'.$sel_adv); ?>"><?php echo $this->lang->line('label_edit'); ?></a> &nbsp;<a href="javascript:isDelsingle2(<?php echo $banner->bannerid;?>,<?php echo $sel_camp;?>,<?php echo $sel_adv;?>)"><?php echo $this->lang->line("label_delete"); ?></a>
				<?php }
				elseif($sel_camp!='' || $sel_camp!=0){ ?>
					<a href="<?php echo site_url('admin/inventory_banners/edit_banner/'.$banner->bannerid.'/'.$sel_camp.'/'.$sel_adv); ?>"><?php echo $this->lang->line('label_edit'); ?></a> &nbsp;<a href="javascript:isDelsingle1(<?php echo $banner->bannerid;?>,<?php echo $sel_camp;?>)"><?php echo $this->lang->line("label_delete"); ?></a>
				<?php
				}
				else{?>
				<a href="<?php echo site_url('admin/inventory_banners/edit_banner/'.$banner->bannerid); ?>"><?php echo $this->lang->line('label_edit'); ?></a> &nbsp;<a href="javascript:isDelsingle(<?php echo $banner->bannerid; ?>)"><?php echo $this->lang->line("label_delete"); ?></a>
				<?php }?>
				 </td>
			</tr>
			<?php endforeach; endif; ?>
			</tbody>
		</table>
	 
