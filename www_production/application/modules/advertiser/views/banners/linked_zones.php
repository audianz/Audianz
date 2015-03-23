<script type="text/javascript">
/**
 * Delete selected items in a table
**/
jQuery('#frm_linked_banners .save1').click(function(){
	var empt = true;
	jQuery('#frm_linked_banners input[type=checkbox]').each(function(){
		if(jQuery(this).is(':checked')) {
			empt = false;
		}
	});
	if(empt == true) 
	{
		jConfirm(<?php echo $this->lang->line('label_advertiser_banners_linked_zones_confirm_remove');?>,<?php echo $this->lang->line('label_advertiser_banners_linked_zones_manage');?>,function(r){
		if(r) {
			jQuery("#frm_linked_banners").submit();
		}
		});
	} 
	
	
	else {
		jConfirm(<?php echo $this->lang->line('label_advertiser_banners_linked_zones_link_select_zones');?>,<?php echo $this->lang->line('label_advertiser_banners_linked_zones_manage');?>,function(r){
		
		if(r) {
			jQuery("#frm_linked_banners").submit();
		}
		});
	}
});

function goToList()
{
var campaignid = document.getElementById('campaignid').value;
document.location.href='<?php echo site_url("advertiser/banners/listing"); ?>/'+campaign_id;
}
	
</script>
	 	
	 	<h1 class="pageTitle"><?php echo $this->lang->line('label_advertiser_banners_linked_zones_title');?></h1>
        
		 <?php if($this->session->flashdata('error_message') != ''): ?>
		 <div class="notification msgerror">
					<a class="close"></a>
					<p><?php echo $this->session->flashdata('error_message'); ?></p>
		 </div>
		 <?php endif; ?>
		 
		 <?php if($this->session->flashdata('success_message') != ''): ?>
		 <div class="notification msgsuccess">
					<a class="close"></a>
					<p><?php echo $this->session->flashdata('success_message'); ?></p>
		 </div>
		 <?php endif; ?>
		 		
        <div class="sTableOptions">
			<h4><?php $this->lang->line('label_advertiser_banners_link_zones');?>"<strong><?php echo view_text($banner_det->campaignname); ?> : <?php echo view_text($banner_det->banner_name); ?></strong>"</h4>	            
        </div><!--sTableOptions-->
        <form name="frm_linked_banners"  id="frm_linked_banners" method="post" action="<?php echo site_url('advertiser/banners/linked_zones_process').'/'.$sel_banner_id.'/'.$campaign_id;?>" >	
	        <input type="hidden" name="campaignid" id="campaignid" value="<?php echo $campaign_id; ?>" />        	
        	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
                <col class="head1" width="5%"/>
                <col class="head0" width="20%" />
                <col class="head1" width="20%"/>
                <col class="head0" width="20%"/>
                <col class="head1" width="20%"/>
            </colgroup>
            <tr>
                <td align="center"><input type="checkbox" class="checkall" /></td>
                <td align="center"><?php echo $this->lang->line('label_inventory_zone_name'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_inventory_zone_website'); ?></td>
		<td align="center"><?php echo $this->lang->line('label_inventory_pricing_model'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_inventory_zone_sizes'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" width="100%" >
                <colgroup>
                    <col class="con1" width="5%"/>
                    <col class="con0" width="20%" />
                    <col class="con1" width="20%" />
                    <col class="con0" width="20%"/>
                    <col class="con1" width="20%"/>
                </colgroup>
              	<?php 
		if($linked_zones_list != FALSE):
		foreach($linked_zones_list as $zone_data):
			if(is_array($mapped_zones['zone']) AND count($mapped_zones['zone'] > 0) AND in_array($zone_data->zoneid,$mapped_zones['zone'])){
				$status = "checked";
			}
			else
			{
				$status = "";
			}
		?>
		<tr>
                    <td align="center">
                    <input type="checkbox" <?php echo $status; ?> name="sel_zone[]" value="<?php echo $zone_data->zoneid; ?>" />                    
                    </td>
                    <td><?php echo view_text($zone_data->zonename);?></td>
                    <td><?php echo view_text($zone_data->publisher_name); ?></td>
		    <td align="center"><?php echo view_text($zone_data->revenue_type_name);?></td>
                    <td align="center" > <?php echo ($zone_data->master_zone != -1)?$zone_data->width." X ".$zone_data->height:"NA"; ?></td>
                </tr>
				<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td align="center" colspan="5"><?php echo $this->lang->line('label_zones_record_not_found'); ?></td>
					</tr>
				<?php endif; ?>
			</table>
			
	     </div><!--sTableWrapper-->
	     <br/>
	     <input type="hidden" name="banner_id" value="<?php echo $sel_banner_id; ?>" />
	     <?php if($linked_zones_list != FALSE): ?>
   	       <input type="submit" class="button button_blue" value="<?php echo $this->lang->line('label_save').' / '.$this->lang->line('label_update');?>" />        	     
   	     <?php endif; ?>
   	     <input type="button" class="button button_blue" value="<?php echo $this->lang->line('label_cancel'); ?>" OnClick="location.href='<?php echo site_url("advertiser/banners/listing").'/'.$campaign_id; ?>';" />
         </form> 
         <br />	 
