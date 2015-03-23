	
			 <!--  Display notification message upon every successfull delete,insert and update operation -->
			
			<?php 
						if($this->session->flashdata('revenue_success_message') != ""):?>
			
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('revenue_success_message'); ?> </p>
								</div>
			<?php
						endif;
						
			 
						if($this->session->flashdata('revenue_delete_message') != ""):?>
			
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('revenue_delete_message'); ?> </p>
								</div>
			<?php
						endif;			
						
						if($this->session->flashdata('revenue_update_message')!=''):?>
			
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('revenue_update_message');?></p>
								</div>
			<?php
						endif;				
						
			?>
			
			<!-- End of displaying notification message -->	
		<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_revenue_type_title');?></h1><!-- Display page title dynamically -->
		<?php /*?><a href="<?php echo site_url("admin/settings_revenue_type/add_type");?>" class="addNewButton">Add Revenue Type</a><?php */?>
		<br />

<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
	  
	  <colgroup>
	  <col class="head0" width="30%" />
	  <col class="head1" width="30%" />
	  <col class="head0" width="30%" />
	  </colgroup>
	  
	<tr>

			<td><?php echo $this->lang->line('label_inventory_revenue_type_pricing_model');?></td>
			<td align="center"><?php echo $this->lang->line('label_inventory_revenue_type_revenue_id');?></td>
			<td align="center"><?php echo $this->lang->line('label_inventory_revenue_type_action');?></td>
	</tr>
	
</table>
	<form action="" id="revenue_type_form" method="post">
	<div class="sTableWrapper">
		  <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
			
				<colgroup>
				<col class="con0" width="30%" />
				<col class="con1" width="30%" />
				<col class="con0" width="30%" />
				</colgroup>
    <?php 
			if(count($type) > 0): //Condition to check whether the record exist or not
			foreach($type as $row):?>
							
				  <tr>
						  <td><?php echo view_text(strtoupper($row->revenue_type)); ?></td>
						  <td align="center"><?php echo view_text($row->revenue_type_value); ?></td>
						  <td align="center">
						  <a href="<?php echo site_url("admin/settings_revenue_type/edit_type/".$row->revenue_id."");?>" >
						  <?php echo $this->lang->line("label_edit"); ?></a>&nbsp;&nbsp;&nbsp;
						  <a href="javascript:confirm_delete(<?php echo $row->revenue_id;?>)">
						  <?php echo  $this->lang->line("label_delete"); ?></a></td>
						  </tr>
						  <?php endforeach;
						  else:?>
   				 <style>
					#delete
					{
					display:none;
					}
					</style>
				 <tr>
      					<td colspan="7"><center><?php echo $this->lang->line("label_records_not_fount"); ?></center></td>
    			</tr>
    <?php endif;?>
  			</table>
</div>
<!--sTableWrapper-->
</form>


	 <script type="text/javascript">
	  
	 function confirm_delete(id)
		{
			jConfirm('<center><?php echo $this->lang->line("label_inventory_revenue_delete_type"); ?></center>',
			'<?php echo $this->lang->line("label_inventory_revenue_type_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/settings_revenue_type/delete_type/");?>/'+id;	
	}				});
					}
		

	
		</script>
