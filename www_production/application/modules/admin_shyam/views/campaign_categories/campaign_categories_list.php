  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
     
	<?php if($this->session->flashdata('message') !=''): ?><div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('message'); ?></p></div><?php endif; ?>
	 <a href="<?php echo site_url("admin/settings_campaign_categories/add_campaign_category"); ?>" class="addNewButton">
	  <?php echo $this->lang->line('label_add_campaign_category'); ?>
	  </a>
     
	<?php if(count($campaign_categories_list) > 0 && $campaign_categories_list !=''): ?>
	    <ul class="submenu">
        	<li <?php echo ($this->uri->segment(3)=="all_list" || $this->uri->segment(3)=="" )?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/settings_campaign_categories'); ?>"><?php echo $this->lang->line("label_all"); ?> (<?php echo $all_records; ?>)
			</a>
			</li>
            		<?php if($active_records !='0'):?>
			<li <?php echo ($this->uri->segment(3)=="active")?"class='current'":""; ?>>
			<a href="<?php echo site_url('admin/settings_campaign_categories/active'); ?>"><?php echo $this->lang->line("label_active"); ?> (<?php echo $active_records; ?>)</a>
			</li>
			<?php endif; ?>
			<?php if($inactive_records !='0'):?>
            <li <?php echo ($this->uri->segment(3)=="inactive")?"class='current'":""; ?>><a href="<?php echo site_url('admin/settings_campaign_categories/inactive'); ?>"><?php echo $this->lang->line("label_inactive"); ?> (<?php echo $inactive_records; ?>)</a></li>
			<?php endif; ?>
        </ul>
	
	<?php else:?>
	<br />
	<?php endif; ?>
        <br />
	
	<?php if(count($campaign_categories_list) > 0 && $campaign_categories_list !=''): ?>        
        <div class="sTableOptions">
        <?php echo $this->pagination->create_links(); ?>
		<a   class="button delete_record"><span><?php echo $this->lang->line("label_delete"); ?></span></a>
            
        </div><!--sTableOptions-->
	<?php endif; ?>

		<form id="frmCampaignCategoriesList" action="<?php echo site_url('admin/settings_campaign_categories/delete_campaign_categories'); ?>" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="3%" />
                <col class="head1" width="5%" />
                <col class="head0" width="20%" />
				<col class="head1" width="20%" />
				<col class="head0" width="20%" />
				<!-- <col class="head1" width="10%" /> -->
	          	<col class="head0" width="15%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" id="checkall" /></td>
                <td><?php echo $this->lang->line('label_s_no'); ?></td>
                <td><?php echo $this->lang->line('label_campaign_category'); ?></td>
				<td><?php echo $this->lang->line('label_added_date'); ?></td>
				<td><?php echo $this->lang->line('label_updated_date'); ?></td>
				<!-- <td align="center"><?php //echo $this->lang->line('label_status'); ?></td> -->
				<td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="3%" />
                    <col class="con1" width="5%" />
                    <col class="con0" width="20%" />
					<col class="con1" width="20%" />
					<col class="con0" width="20%" />
		    		<!-- <col class="con1" width="10%" /> -->
                    <col class="con0" width="15%" />
                </colgroup>
				<?php 
					if(count($campaign_categories_list) > 0 && $campaign_categories_list !=''):
						$i=$offset;
						foreach($campaign_categories_list as $row):
						?>
								<tr>
									<td align="center"><input name="sel_campaign_categories[]" type="checkbox" value="<?php echo $row->category_id; ?>" /></td>
									<td><?php echo $i++; ?></td>
									<td><?php echo view_text($row->category_name); ?></td>
									<td><?php echo view_text($row->added_date); ?></td>
									<td>
									<?php 
										if($row->updated_date !='0000-00-00'):
											echo view_text($row->updated_date); 
										else:
											echo $this->lang->line('label_not_updated_yet');
										endif;		
									?></td>
									
								<!--	<td align="center">
									<?php 
												/*****Checking the Status ******/
												if($row->status =='1'):
										?>	
												<a href="javascript:change_Status(<?php echo view_text($row->category_id); ?>)" 
												 alt="<?php echo $this->lang->line("label_active"); ?>"  
												title="<?php echo $this->lang->line('title_click_to_inactive'); ?>">
												<?php echo $this->lang->line('label_active'); ?></a>
										<?php else: ?>
												<a href="javascript:change_Status(<?php echo view_text($row->category_id); ?>)" 
												 alt="<?php echo $this->lang->line("label_inactive"); ?>"  
												title="<?php echo $this->lang->line('title_click_to_active'); ?>">
												<?php echo $this->lang->line('label_inactive'); ?></a>
										<?php endif; ?>		
									</td>
									-->

									<td align="center">
										<a href="<?php echo site_url('admin/settings_campaign_categories/edit_campaign_category/'.$row->category_id);?>">
										<?php echo $this->lang->line("label_edit"); ?></a> &nbsp; 
										<a href="javascript:isDelsingle(<?php echo $row->category_id; ?>)">
										<?php echo $this->lang->line("label_delete"); ?></a>
										
									</td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7" align="center"><?php echo $this->lang->line("label_campaign_category_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
				?>

            </table>
			</form>
	     </div><!--sTableWrapper-->
		  <script type="text/javascript">
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
							jAlert('<?php echo $this->lang->line("alert_no_item_selected");?>','<?php echo $page_title; ?>');
						} else {
	
							jConfirm('<?php echo $this->lang->line("confirmation_selected_delete_campaign_categories"); ?>','<?php echo $this->lang->line("confirmation_select_delete_campaign_categories_title"); ?>',function(r){
							
							if(r)
							{
								jQuery("#frmCampaignCategoriesList").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
									unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
							
							
										
							
						}
						//jQuery(".checkall").attr('checked',false);
					});

							
					
					function isDelsingle(category_id)
					{
						jConfirm('<?php echo $this->lang->line("confirmation_delete_campaign_categories"); ?>','<?php echo $this->lang->line("confirmation_deletion_category_title"); ?>',function(r){
					if(r)
					{
						document.location.href	= '<?php echo site_url('admin/settings_campaign_categories/delete_campaign_categories/');?>/'+category_id;	
	}				});
					}
					
					function change_Status(category_id)

					{

						jConfirm('<?php echo $this->lang->line("confirmation_status_campaign_categories"); ?>','<?php echo $this->lang->line("confirmation_status_category_title"); ?>',function(r){
					if(r)
					{	
						document.location.href	= '<?php echo site_url('admin/settings_campaign_categories/change_status/');?>/'+category_id;	
	}
						});

					}
	</script>
