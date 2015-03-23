<?php if($page_title != ''): ?>
	<h1 class="pageTitle"><?php echo $this->lang->line('label_static_pages_page_title'); ?></h1>
     <?php endif; ?> 
	 
	 <!-- Displaying notification message to user -->
	 		
		
			<?php 
						if($this->session->flashdata('pages_update_message') != ""):
			   ?>
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('pages_update_message'); ?> </p>
								</div>
			<?php
						endif;
						
			
						if($this->session->flashdata('pages_delete_message') != ""):
			   ?>
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('pages_delete_message'); ?> </p>
								</div>
			<?php
						endif;			
		
						if($this->session->flashdata('pages_success_message') != ""):
			   ?>
								<div class="notification msgsuccess"><a class="close"></a>
								<p><?php echo $this->session->flashdata('pages_success_message'); ?> </p>
								</div>
			<?php
						endif;				
		
		
		
			if($this->session->flashdata('pages_status_message') != ""):
   			?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('pages_status_message'); ?> </p>
					</div>
		<?php
		endif;
		?>			
		
		<?php 
			if($this->session->flashdata('pages_status_deactivate_message') != ""):
   ?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('pages_status_deactivate_message'); ?> </p>
					</div>
		<?php
		endif;
		?>				
	 <!-- End of displaying notification message-->
	 	<div align="right">	
		<a href="<?php echo site_url("admin/pages/add_page"); ?>" class="iconlink" align="center">
		<img src="<?php echo base_url();?>assets/images/icons/small/white/plus.png"	class="mgright5" alt="" /> <span><?php echo strtoupper($this->lang->line('label_static_pages_page_add_new_page'));?></span></a>	
		</div>	
	  <form name="page_delete_form" id="page_delete_form" action="<?php echo site_url("admin/pages/delete_page");?>" method="post">
	   <div class="sTableOptions">
        	<a class="button delete_page delete" id="delete"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
        </div>
				
		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="4%" />
                <col class="head1" width="10%" />
                <col class="head0" width="30%" />
                <col class="head1" width="10%" />
                <col class="head0" width="20%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" /></td>
				<td align=""><?php echo $this->lang->line('label_static_pages_s_no');?></td>
                <td align=""><?php echo $this->lang->line('label_static_pages_page_name');?></td>
                <td align=""><?php echo $this->lang->line('label_static_pages_status');?></td>
               	<td align=""><?php echo $this->lang->line('label_action');?></td>
            </tr>
        </table>
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
				<colgroup>
                    <col class="con0" width="4%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="30%" />
                    <col class="con1" width="10%" />
                	<col class="con0" width="20%" />
				</colgroup>		
		<?php 
					
					if(count($pages_list) > 0):
					$counter=1;
					foreach($pages_list as $row):
				?>
                <tr>
					<td align="center"><input type="checkbox" name="pages[]" value="<?php echo $row->pageid;?>" /></td>
					<td align=""><?php echo $counter++; ?></td>
                    <td ><?php echo view_text(ucfirst($row->page_title)); ?></td>
					
					<?php if($row->status==0):?>
				  <td align=""><a href="javascript:confirm_status1(<?php echo $row->pageid;?>)" 
					alt="<?php echo $this->lang->line("label_active"); ?>"  
					title="<?php echo $this->lang->line('label_static_pages_page_click_to_activate_page'); ?>">
					<span class="status_icon"><img src="<?php echo base_url();?>/assets/images/icons/inactive.png" />
					</span></a></td>
					
					<?php else:?>
					
					<td align=""><a  style="text-decoration:none;" 
					href="javascript:confirm_status(<?php echo $row->pageid;?>)" 
					 alt="<?php echo $this->lang->line("label_active"); ?>"  
					title="<?php echo $this->lang->line('label_static_pages_page_click_to_deactivate_page'); ?>">
					<span class="status_icon"><img src="<?php echo base_url();?>/assets/images/icons/active.png" />
					</span></a></td>
					
					<?php endif;?>
                    
					<td align=""><a class="editicon" href="<?php echo site_url("admin/pages/edit_page/".$row->pageid.""); ?>">
					<?php echo $this->lang->line("label_edit"); ?>
					</a>&nbsp;&nbsp;<a class="deleteicon" href="javascript:isdelete(<?php echo $row->pageid;?>)">
					<?php echo $this->lang->line("label_delete"); ?></a></td>
               		</tr>
					<?php endforeach;
					else:?>
					<tr><td colspan="7">
					<style>
					#delete
					{
					display:none;
					}
					</style>
					<p align="center">
					<?php echo $this->lang->line("label_records_not_fount");?>
					</p>
					<?php
					endif;
		?></td></tr>
			</table>
			
	     </div>
		 </form>
		  <script type="text/javascript">
          
         function isdelete(id)
		{
			jConfirm('<center><?php echo $this->lang->line("label_static_page_sure_to_delete_selected_page"); ?></center>',
			'<?php echo $this->lang->line("label_static_pages_page_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url('admin/pages/delete_page/');?>/'+id;	
	}				});
					}
				
		
		
		 function confirm_status1(id)
		{
			jConfirm('<center><?php echo $this->lang->line("label_static_pages_page_click_to_apage"); ?></center>',
			'<?php echo $this->lang->line("label_static_pages_page_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url('admin/pages/page_status_activate/');?>/'+id;	
	}				});
					
				
		}
		
		 function confirm_status(id)
		{
			jConfirm('<center><?php echo $this->lang->line("lang_static_pages_page_click_to_dpage"); ?></center>',
			'<?php echo $this->lang->line("label_static_pages_page_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url('admin/pages/page_status_deactivate/');?>/'+id;	
	}				});
					}
		
		/* Delete selected items from a table */
			
		jQuery('.sTableOptions .delete_page').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<center><?php echo $this->lang->line("lang_website_no_item_selected");?></center>','<?php echo $this->lang->line("label_static_pages_page_title"); ?>');
						} else {
							jConfirm('<center><?php echo $this->lang->line("label_static_page_sure_to_delete_selected_page"); ?></center>','<?php echo $this->lang->line("label_static_pages_page_title"); ?>',function(r){
							
							if(r)
							{
								jQuery("#page_delete_form").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
						}

					});
					
					
		</script>
		
