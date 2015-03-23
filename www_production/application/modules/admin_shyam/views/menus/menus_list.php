<!-- Displaying notification message to users -->
<?php 
			if($this->session->flashdata('menu_insert_message')!=''):
?>
					<div class="notification msgsuccess"><a class="close"></a>
					<p><?php echo $this->session->flashdata('menu_insert_message');?></p>
					</div>
<?php
			endif;

			if($this->session->flashdata('menu_delete_message')!=''):
?>	
					<div class="notification msgsuccess"><a class="close"></a>
					<p><?php echo $this->session->flashdata('menu_delete_message');?></p>
					</div>
<?php	
			endif;	

			if($this->session->flashdata('menu_update_message')!=''):
?>
					<div class="notification msgsuccess"><a class="close"></a>
					<p><?php echo $this->session->flashdata('menu_update_message');?></p>
					</div>
<?php
			endif;	
?>
<!-- end of displaying notification message to user -->
<br />
<h1 class="pageTitle"><?php echo $this->lang->line('lang_static_page_menu_title'); ?></h1>
       
	<div align="right">
		 <a href="<?php echo site_url("admin/menus/add_menu");?>" class="iconlink" align="center">
		 <img src="<?php echo base_url();?>assets/images/icons/small/white/plus.png" class="mgright5" alt="" /> <span>
		 <?php echo $this->lang->line('lang_static_page_feildset');?></span></a>
	</div>
	<form action="<?php echo site_url("admin/menus/delete_menu");?>" name="menu_add_form" id="menu_add_form" method="post">

       <div class="sTableOptions">
        	
            <?php echo $this->pagination->create_links(); ?>
        	<a class="button delete_menu delete" id="delete"><span><?php echo $this->lang->line("label_delete"); ?></span></a>

        </div>
				
		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="10%" />
                <col class="head0" width="50%" />
				<col class="head1" width="25%" />
            </colgroup>
            <tr>
            	<td align="center"><input type="checkbox" class="checkall" name="menu[]" /></td>
				<td><?php echo $this->lang->line('lang_static_page_menu_sno');?></td>
                <td><?php echo $this->lang->line('lang_static_page_menu_name');?></td>
				<td align=""><?php echo $this->lang->line('lang_static_page_menu_action');?></td>
            </tr>
        </table>
        
    <div class="sTableWrapper">
		
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="50%" />
                   <col class="con1" width="25%" />
                </colgroup>
				<?php
				$c=1;
				if($menu_list_parent != FALSE && count($menu_list_parent) > 0)
					{
						for($i=0;$i<count($menu_list_parent);$i++)
						{
							?>
							<tr>
							
							<?php 
							
							//Disable edit/delete for home menu
							if($menu_list_parent[$i]->menu_name != "home")
							{
								?>
								<td align="center"><input type="checkbox" name="delete_menu[]" value="<?php echo $menu_list_parent[$i]->id;?>"/></td>
							<td><?php echo $c;
									$c++;?></td>
							<td><?php echo ucfirst($menu_list_parent[$i]->menu_name);?></td>
								<td align=""><a href="<?php echo site_url("admin/menus/edit_menu/".$menu_list_parent[$i]->id."");?>"><?php echo $this->lang->line('label_edit');?>
								 </a>&nbsp; <a href="javascript:menu_confirm_parent(<?php echo $menu_list_parent[$i]->id;?>)">
								 <?php echo $this->lang->line('label_delete');?></a></td>
								<?php 
							}
							else
							{
								?>
								<td align=""><input disabled="disabled" type="checkbox" name="delete_menu[]" value="<?php echo $menu_list_parent[$i]->id;?>"/></td>
							<td><?php echo $c;
									$c++;?></td>
							<td><?php echo ucfirst($menu_list_parent[$i]->menu_name);?></td>
								<td align="" style="visibility:hidden;"><a href="<?php echo site_url("admin/menus/edit_menu/".$menu_list_parent[$i]->id."");?>"><?php echo $this->lang->line('label_edit');?>
								 </a>&nbsp; <a href="javascript:menu_confirm_parent(<?php echo $menu_list_parent[$i]->id;?>)">
								 <?php echo $this->lang->line('label_delete');?></a></td>
								 <?php
							 }
									
										//Get all child menus for the parent menu
										$data=$this->mod_static_pages->get_parent_childs($menu_list_parent[$i]->id);
										if($data != 0)
										{
											for($j=0;$j<count($data);$j++)
											{
													?>
													<tr>
													<td align=""><input type="checkbox" name="delete_menu[]" value="<?php echo $data[$j]->id;?>"/></td>
													<td></td><td><?php
													echo $this->lang->line('label_static_page_sub_menu').ucfirst($data[$j]->menu_name)."";?>
													<td align="" ><a href="<?php echo site_url("admin/menus/edit_menu/".$data[$j]->id."");?>"><?php echo $this->lang->line('label_edit');?></a>&nbsp; <a href="javascript:menu_confirm(<?php echo $data[$j]->id;?>)"><?php echo $this->lang->line('label_delete');?></a></td>
													<?php
											}
										}	
						}
					}
					else
					{
						?><style>
						#delete
						{
						display:none;
						}
						</style>
						<p align="center">
						<?php echo $this->lang->line('label_records_not_fount');
					}?>
					</p>
           </table> 	
		
	</div> 
</form>			
<script type="text/javascript">
	
	  function menu_confirm(id)
		{
			jConfirm('<center><?php echo $this->lang->line("lang_static_page_menu_sure_delete_selected"); ?></center>',
			'<?php echo $this->lang->line("lang_static_page_menu_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/menus/delete_menu/");?>/'+id;	
	}				});
					}
					
	function menu_confirm_parent(id)
		{
			jConfirm('<center><?php echo $this->lang->line("lang_static_page_menu_sure_delete_selected_parent"); ?></center>',
			'<?php echo $this->lang->line("lang_static_page_menu_title"); ?>',function(r){
					if(r)
					{
					document.location.href	= '<?php echo site_url("admin/menus/delete_menu/");?>/'+id;	
	}				});
					}

				
		
	
	jQuery('.sTableOptions .delete_menu').click(function(){
						var empt = true;
						jQuery('.sTable input[type=checkbox]').each(function(){
							if(jQuery(this).is(':checked')) {
								empt = false;
							}
						});
						if(empt == true) {
							jAlert('<center><?php echo $this->lang->line("lang_website_no_item_selected");?></center>','<?php echo $this->lang->line("lang_static_page_menu_title"); ?>');
						} else {
							jConfirm('<center><?php echo $this->lang->line("lang_static_page_menu_sure_delete_selected"); ?></center>','<?php echo $this->lang->line("lang_static_page_menu_title"); ?>',function(r){
							if(r)
							{
								jQuery("#menu_add_form").submit();
							}else{
								jQuery("#checkall").attr('checked',false);
							}
								unchk.uncheckboxes(); // Used to trigger  for unchecking of items after the process has been completed.						
							});
						}

					});	

</script>		
