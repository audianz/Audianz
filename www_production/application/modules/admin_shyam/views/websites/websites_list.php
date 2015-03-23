 <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
	<script>
	jQuery(document).ready(function() {
			jQuery('#userlist').dataTable( {
			"sPaginationType": "full_numbers"
			});
			
		});
	</script>
			 <!--  Display notification message upon every successfull delete,insert and update operation -->

			<?php 
						if($this->session->flashdata('site_success_message') != ""):
			   ?>
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('site_success_message'); ?> </p>
								</div>
			<?php
						endif;
						
			 
						if($this->session->flashdata('site_notification_message') != ""):
			   ?>
								<div class="notification msgerror"><a class="close"></a>
								<p><?php echo $this->session->flashdata('site_notification_message'); ?> </p>
								</div>
			<?php
						endif;
						
			
						if($this->session->flashdata('site_delete_message')!=''):
			?>	
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('site_delete_message');?></p>
								</div>
			<?php	
						endif;	
			
						if($this->session->flashdata('site_update_message')!=''):
			?>
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('site_update_message');?></p>
								</div>
			<?php
						endif;	
			?>
			
			<!-- End of displaying notification message -->	
			

<br />
				<!-- Display page title dynamically  -->
				
				<?php if($page_title!=''):?>
				<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_websites_page_title');?></h1>
				<?php endif;?>
				
				<!-- End of page title -->
				<a href="<?php echo site_url("admin/inventory_websites/add_site");?>" class="addNewButton"><?php echo $this->lang->line('label_inventory_websites_addsite_button');?></a>
				<br /><br />
	<?php if(!empty($website_list))
		{
		?>
		<div class="sTableOptions"> <?php echo $this->pagination->create_links();?> 
		 <a class="button delete_site" id="delete">
		<span><?php echo $this->lang->line("label_delete"); ?></span></a>
		</div><?php }?>
		<!--sTableOptions-->
				
				<form  class="formular" id="website_form"  name="website_form" action="<?php echo site_url('admin/inventory_websites/delete_site'); ?>" method="post">
					
	  <table cellpadding="0" cellspacing="0" class="dyntable" id="userlist" width="100%">
		<thead>
                <tr>
					<th class="head1"><input type="checkbox" class="checkall" id="checkall" style="margin-left: 8px;"/></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_websites_td1');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_inventory_websites_td2');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_inventory_websites_td3');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_inventory_websites_td4');?></th>

                </tr>
            </thead>
		<colgroup>
			<col class="con0" width="4%" />
			<col class="con1" width="25%" />
			<col class="con0" width="25%" />
			<col class="con1" width="20%" />
			<col class="con0" width="20%" />
		</colgroup>
		
		<?php 
				if(count($website_list) > 0): //Condition to check whether the record exist or not
					foreach($website_list as $row):?>

					<tr>
							 	<td align="center"><input type="checkbox" value="<?php echo $row->id; ?>" name="delete_site[]" id="checkbox"/></td>
							 	<td><?php echo view_text($row->username); ?></td>
							  	<td><?php echo view_text($row->websitename); ?></td>
							  	<td align="">
							   	<a href="<?php echo site_url("admin/inventory_zones/add_zones/".$row->affiliateid."");?>" ><?php echo $this->lang->line("label_add"); ?></a> -
								<a href="<?php echo site_url("admin/inventory_zones/listing/".$row->affiliateid."");?>" ><?php echo $this->lang->line("label_view"); ?></a></td>
								<td align="">
								<a href="<?php echo site_url("admin/inventory_websites/edit_site/".$row->id."");?>" ><?php echo $this->lang->line("label_edit"); ?></a>&nbsp;
								<a href="javascript:delsingle(<?php echo $row->id;?>,<?php echo $row->accountid;?>)" >
								<?php echo $this->lang->line("label_delete"); ?></a></td>
					</tr>
			
		<?php endforeach;?>
		<?php endif;?>
	  </table>
	  
	 <script type="text/javascript">
	  
	 function delsingle(id,account_id)
					{
						jConfirm('<center><?php echo $this->lang->line("lang_website_are_you_sure_to"); ?></center>','<?php echo $this->lang->line("lang_manage_websites"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url('admin/inventory_websites/delete_site/');?>/'+id+'/'+account_id;	
	}				});
					}
		

					/* Delete selected items from a table */
					
	jQuery('.sTableOptions .delete_site').click(function(){
						var empt = true;
						jQuery('.dyntable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<center><?php echo $this->lang->line("lang_website_no_item_selected");?></center>','<?php echo $this->lang->line("lang_manage_websites"); ?>');
						} else {
							jConfirm('<center><?php echo $this->lang->line("lang_website_are_you_sure_to"); ?></center>','<?php echo $this->lang->line("lang_manage_websites"); ?>',function(r){
							
							if(r)
							{
								jQuery("#website_form").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
						}

					});
		</script>
		
		
	  
	 
	  
</div>
<!--sTableWrapper-->
