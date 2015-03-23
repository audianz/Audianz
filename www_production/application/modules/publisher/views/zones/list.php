<div>  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
	<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_zones_page_title'); ?></h1>
     <?php endif; ?> 
	 
	 <?php 
			if($this->session->flashdata('zones_success_message') != ""):
   ?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('zones_success_message'); ?> </p>
					</div>
<?php
			endif;?>
			<?php echo validation_errors();?>   
		       
        <a href="<?php echo site_url("publisher/zones/add_zones"); ?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_addzones_fieldset'); ?></a>
        <br />
		<br />
        <form name="list" id="list" action="<?php echo site_url('publisher/zones/delete_zones');?>" method="post">
        <div class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?>
		<a class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
        </div><!--sTableOptions-->
		
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            		<col class="head0" width="10%" />
                    <col class="head1" width="30%" />
                    <col class="head0" width="10%" />
                    <col class="head1" width="25%" />
                    <col class="head0" width="25%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
                <td><?php echo $this->lang->line("label_inventory_zonesname"); ?></td>
				<td align=""><?php echo $this->lang->line("label_inventory_model"); ?></td>
                <td align=""><?php echo $this->lang->line("label_inventory_linkedcode"); ?></td>
				<td align=""><?php echo $this->lang->line("label_inventory_action"); ?></td>
        	</tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="10%" />
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
                    <td align="center"><input type="checkbox" id="checkvals" value="<?php echo $row->zoneid; ?>" name="check[]"/></td>
                    <td><?php echo view_text($row->zonename); ?></td>
                    <td align=""><?php echo view_text($row->pricing); ?></td>
                   	<td align=""><a href="<?php echo site_url("publisher/zones/linkedbycampaigns/".$row->zoneid."/".$row->affiliateid); ?>"><?php echo $this->lang->line("label_inventory_banners"); ?></a> &nbsp; 
					/ &nbsp;<a href="<?php echo site_url("publisher/zones/invocation/".$row->zoneid); ?>" class="invocation"><?php echo $this->lang->line("label_inventory_invocation"); ?></a></td>
				    <td align=""><a href="<?php echo site_url("publisher/zones/edit_zones/".$row->zoneid.""); ?>"><?php echo $this->lang->line("label_edit"); ?></a> &nbsp; <a href=" javascript:isDeleteZone(<?php echo $row->zoneid;?>)"  class="delete_zones"><?php echo $this->lang->line("label_delete"); ?></a></td>
                </tr>
				<?php
						endforeach;
						else:
				?>
				<tr>
                    <td colspan="7"><?php echo $this->lang->line("label_zones_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
					endif;
				?>
				  </table>
				
			</form>
			</div>
			</div></div></ul>
		 <!--sTableWrapper-->
 <!--left-->
  
  
  <script type="text/javascript">
          
    
	
		function isDeleteZone(zone_id)
		{
			jConfirm('<?php echo $this->lang->line("label_confirm_delete_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
				if(r)
				{
																
							document.location.href='<?php echo site_url("publisher/zones/delete_zones/"); ?>/'+zone_id;
				}			
		});
		}

		/**
		 * Delete selected items in a table
		**/
		jQuery('.sTableOptions .delete_record').click(function(){
		
				var empt = true;
				
				jQuery('.sTable input[type=checkbox]').each(function(){
				
						if(jQuery(this).is(':checked')) {
								empt = false;
						}
				});
				
				if(empt == true)
				{
						jAlert('<center><?php echo $this->lang->line("label_no_item_selected"); ?></center>');
				} 
				else 
				{
						jConfirm('<?php echo $this->lang->line("label_confirm_delete_selected_zone"); ?>','<?php echo $this->lang->line("label_confirm_box"); ?>',function(r){
						//If the result is trur or Ok buttton is clicked
						if(r)
						{
							jQuery("#list").submit();
						}else{
							jQuery(".checkall").attr('checked',false);
							jQuery(this).parents('tr').removeClass('selected');
						}
						});
						
				}
		});
</script>
