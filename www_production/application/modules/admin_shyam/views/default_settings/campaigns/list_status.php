       <!--  Display notification message upon every successfull delete,insert and update operation -->
	    <?php 
			
			if($this->session->flashdata('campaign_success_message') != ""):?>
	   				
					<div class="notification msgsuccess"><a class="close"></a>
					
  					<p><?php echo $this->session->flashdata('campaign_success_message'); ?> </p>
					
					</div>
		<?php
			endif;
			if($this->session->flashdata('campaign_delete_message') != ""):?>
	   				
					<div class="notification msgsuccess"><a class="close"></a>
					
  					<p><?php echo $this->session->flashdata('campaign_delete_message'); ?> </p>
					
					</div>
		<?php
			endif;	
			
			if($this->session->flashdata('campaign_update_message')!=''):?>

					<div class="notification msgsuccess"><a class="close"></a>
					
					<p><?php echo $this->session->flashdata('campaign_update_message');?></p>
					
					</div>
					
		<?php endif;	?>	
				
		<!-- End of displaying notification message -->	
		<?php if($page_title!=''):?>
			<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_campaign_status_title');?></h1><!-- Display page title dynamically -->
		<?php endif;?>
    	<?php /*?><a href="<?php echo site_url("admin/settings_campaign_status/add_status");?>" class="addNewButton"><?php echo $this->lang->line("label_inventory_campaign_status_feildset");?></a><?php */?>
       	<br />
		
		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	
			<colgroup>
            	<col class="head0" width="30%" />
                <col class="head1" width="30%" />
                <col class="head0" width="30%" />
        	</colgroup>
            
			<tr>
            	<td ><?php echo $this->lang->line("label_inventory_campaign_status");?></td>
				<td align="center"><?php echo $this->lang->line("label_inventory_campaign_status_value");?></td>
				<td align="center"><?php echo $this->lang->line("label_inventory_campaign_status_action");?></td>
           </tr>
		   
		</table>
        
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
					<td><?php echo view_text(strtoupper($row->status)); ?></td>
					<td align="center"><?php echo view_text(strtoupper($row->campaign_status_value)); ?></td>
					<td align="center"><a href="<?php echo site_url("admin/settings_campaign_status/edit_status/".$row->campaign_status_id."");?>">
					<?php echo $this->lang->line("label_edit"); ?>
					</a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:confirm_delete(<?php echo $row->campaign_status_id;?>)" >
					<?php echo $this->lang->line("label_delete"); ?></a>
			</tr>
			
		<?php endforeach;
			else: ?>
			<style>
					#delete
					{
					display:none;
					}
					</style>
			<tr>
					<td><center><?php echo $this->lang->line("label_records_not_fount"); ?></center></td>
			</tr>
		<?php
			endif;
		?>

	</table>
	     </div><!--sTableWrapper-->
		 
		 
		 <script type="text/javascript">
	  
	  function confirm_delete(id)
		{
			jConfirm('<center><?php echo $this->lang->line("label_inventory_campaign_delete_status"); ?></center>',
			'<?php echo $this->lang->line("label_inventory_campaign_status_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/settings_campaign_status/delete_status/");?>/'+id;	
	}				});
					}
				
		
	
		
					
			</script>		
		 
		 
		
		 
