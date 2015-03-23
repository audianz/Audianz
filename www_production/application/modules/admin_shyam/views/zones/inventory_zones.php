<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
	<script>
	jQuery(document).ready(function() {
			jQuery('#userlist').dataTable( {
			"sPaginationType": "full_numbers"
			});
			
		});
	</script>
	  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != '') : ?><h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_zones_page_title'); ?></h1><?php endif; ?> 
	 
	 <?php if($this->session->flashdata('zones_success_message') != "") : ?><div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('zones_success_message'); ?> </p></div><?php endif; ?>
	<?php echo validation_errors();?>
		       
	<a href='<?php echo site_url("admin/inventory_zones/add_zones/".$affiliateid);?>' class="addNewButton"><?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?></a>
        <br />
		<br />
        <form name="list" id="list" action="<?php echo site_url('admin/inventory_zones/delete_zones/'.$affiliateid);?>" method="post">
        <?php if(!empty($zones_list)) { ?>
        <div class="sTableOptions">
        	<?php echo $this->pagination->create_links(); ?>
			<a class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
		</div><!--sTableOptions-->
		<?php } ?>
		
        
        
        
            <table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">

            <thead>
                <tr>
					<th class="head1"><input type="checkbox" class="checkall" id="checkall" style="margin-left: 8px;"/></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_zonesname');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_inventory_model');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_linkedcode');?></th>
		    <th class="head1"><?php echo 'RTB';//$this->lang->line('label_inventory_linkedcode');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_action');?></th>
				</tr>
            </thead>
                <colgroup>
                    <col class="con0" width="4%" />
                    <col class="con1" width="30%" />
                    <col class="con0" width="10%" />
                    <col class="con1" width="25%" />
                    <col class="con0" width="25%" />
					</colgroup>
				<?php 
				
					if(count($zones_list) > 0):
					 if(is_array($zones_list)): 

					foreach($zones_list as $row):
				?>
                <tr>
                    <td align="center"><input type="checkbox" value="<?php echo $row->zoneid; ?>" name="check[]"/></td>
                    <td><?php echo view_text($row->zonename); ?></td>
                    <td align=""><?php echo view_text($row->pricing); ?></td>
                   	<td align=""><a href="<?php echo site_url('admin/inventory_zones/linkedbycampaigns/'.$row->zoneid.'/'.$row->affiliateid); ?>"><?php echo $this->lang->line("label_inventory_banners"); ?></a> &nbsp; 
					/ &nbsp;<a href="<?php echo site_url('admin/inventory_zones/invocation/'.$row->affiliateid.'/'.$row->zoneid); ?>" class="invocation"><?php echo $this->lang->line("label_inventory_invocation"); ?></a></td>
					<td><?php if($row->rtb==1): ?><img src="<?php echo base_url(); ?>/assets/images/icons/success.png"><?php endif; ?></td>
				    <td align=""><a href="<?php echo site_url('admin/inventory_zones/edit_zones/'.$row->affiliateid.'/'.$row->zoneid.''); ?>"><?php echo $this->lang->line("label_edit"); ?></a> &nbsp; <a href=" javascript:isDeleteZone('<?php echo $row->affiliateid; ?>', '<?php echo $row->zoneid;?>')" class="delete_zones"><?php echo $this->lang->line("label_delete"); ?></a></td>
                </tr>
				<?php endforeach;?>
					
				<?php endif; endif;?>
				 </table>
				
			</form>
			
		 
  
  <script type="text/javascript">
          
    
	
		function isDeleteZone(affiliateid, zone_id)
		{
			jConfirm('<?php echo $this->lang->line("label_confirm_delete_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
				if(r)
				{										
					document.location.href='<?php echo site_url("admin/inventory_zones/delete_zones/"); ?>/'+affiliateid+'/'+zone_id;
				}			
		});
		}

		/**
		 * Delete selected items in a table
		**/
		jQuery('.sTableOptions .delete_record').click(function(){
		
				var empt = true;
				
				jQuery('.dyntable input[type=checkbox]').each(function(){
				
						if(jQuery(this).is(':checked')) {
								empt = false;
						}
				});
				
				if(empt == true) {
						jAlert('<?php echo $this->lang->line("label_no_item_selected"); ?>');
				} else {
						jConfirm('<?php echo $this->lang->line("label_confirm_delete_selected_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
						//If the result is trur or Ok buttton is clicked
						if(r)
						{
							jQuery("#list").submit();
						}else{
							jQuery(".checkall").attr('checked',false);
						}
						unchk.uncheckboxes(); //for unchecking the checkboxes.
						
						});
						
				}
		});
</script>
