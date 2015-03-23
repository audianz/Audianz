  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != '') : ?><h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_mobile_page_title'); ?></h1><?php endif; ?> 
	 
	 <?php if($this->session->flashdata('zones_success_message') != "") : ?><div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('zones_success_message'); ?> </p></div><?php endif; ?>
	<?php echo validation_errors();?>
		
		<br />
        <form name="list" id="list" action="<?php echo site_url('admin/inventory_zones/delete_zones/'.$affiliateid);?>" method="post">
        <div class="sTableOptions">
        	<?php echo $this->pagination->create_links(); if(!empty($zones_list)) {	?>
			
			<br/><br/>
			<?php }?>        
		</div><!--sTableOptions-->
		
        <table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="10%" />
                <col class="head1" width="30%" />
                <col class="head0" width="40%" />
               
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
                <td><?php echo $this->lang->line("label_zone_name"); ?></td>
				
                
				<td align="center"><?php echo $this->lang->line("label_zone_mobile_sdk"); ?></td>
        	</tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="10%" />
                    <col class="con1" width="30%" />
                    <col class="con0" width="40%" />
                  
					</colgroup>
				<?php 
				
					if(count($zones_list) > 0):
					 if(is_array($zones_list)): 

					foreach($zones_list as $row):
				?>
                <tr>
                    <td align="center"><input type="checkbox" value="<?php echo $row->zoneid; ?>" name="check[]"/></td>
                    <td><?php echo view_text($row->zonename); ?></td>
                    
                   	
				    <td align="center"><a href="<?php echo site_url("admin/inventory_zones_sdk/edit_zones_sdk/".$row->affiliateid."/".$row->zoneid.""); ?>">Mobile SDK</a> </td>
                </tr>
				<?php
						endforeach;
						else:
				?>
				<tr>
                    <td colspan="7"><strong><center><?php echo $this->lang->line("label_zones_record_not_found"); ?></center></strong></td>
                </tr>
				<?php
					endif;
					endif;
				?>
				  </table>
				
			</form>
			
		 <!--sTableWrapper-->
 <!--left-->
  </div>
  
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
				
				jQuery('.sTable input[type=checkbox]').each(function(){
				
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
