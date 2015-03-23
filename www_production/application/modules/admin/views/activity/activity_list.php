	<?php  
		if($this->session->flashdata('message') !=''):
	?>
		 <div class="notification msgsuccess">
				<a class="close"></a>
				<p>
					 <?php 
								
								echo $this->session->flashdata('message');
								
					?>
				</p>
			</div>		
	<?php 
		endif;
	?>
		<form id="frmDevicecapabilityList" action="<?php echo site_url('admin/user_activity/activity_process'); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $page_title; ?></legend>
					
				   <?php 
					if(count($task) > 0 && $task != ''):
						$i	=$offset;
						foreach($task as $row):
						if(in_array($row->task_id, $selecttask)) :
						?>				
            	   <p >
                       <label for="man_name"></label>
                       <input name="activity[]" type="checkbox" checked="checked"  style="margin-right:5px" value="<?php echo $row->task_id; ?>" />
                         <?php echo $row->task_desc; ?>  
					 </p>
					 <?php else : ?>
					 <p >
                      <label for="man_name"></label>
                      <input name="activity[]" type="checkbox" style="margin-right:5px" value="<?php echo $row->task_id; ?>" />
                         <?php echo $row->task_desc; ?>
					 </p>
                         	<?php
						endif;
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="2"><?php echo $this->lang->line("label_activity_record_not_found"); ?></td>
                </tr>
				<?php
					endif;
				?>           
                    
                    <p>
                    	<button><?php echo $this->lang->line("label_update"); ?></button>
						<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
					
                    
                </fieldset>
            </div><!--form-->
			
          <input type="hidden" name="userid" value="" />
	       <input type="hidden" name="usertype" value="MANAGER" />	 
        
        </form>
		<script>
			function goToList(){
			document.location.href='<?php echo site_url('admin/dashboard'); ?>';
			}
		</script>
