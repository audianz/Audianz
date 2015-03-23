<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_add_tracker").validationEngine();
	});
</script>

		<h1 class="pageTitle"><?php echo $this->lang->line('label_add_tracker_page_title'); ?></h1>
    
<?php if($this->session->userdata('error_message') != ''): ?>
	 <div class="notification msgerror">
         <a class="close"></a>
         <p><?php echo $this->session->userdata('error_message'); ?></p>
     </div>
<?php $this->session->unset_userdata('error_message'); endif; ?>
<form id="adv_add_tracker" name="adv_add_tracker" action="<?php echo site_url("admin/inventory_advertisers/tracker_process"); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('label_add_tracker_page_title'); ?></legend>
                    
                    <p>
                    	<label for="tracker_name"><?php echo $this->lang->line('label_tracker_name'); ?><span style="color:#FF0000;" >*</span></label>
                        <input value="<?php echo form_text(set_value('tracker_name')); ?>" type="text" alt="<?php echo $this->lang->line('label_enter_tracker_name'); ?>" name="tracker_name"  id="tracker_name" class="validate[required] sf" />
                    </p>
                     <p>
                    	<label for="description"><?php echo $this->lang->line('label_tracker_desc'); ?></label>
                        <textarea name="description" alt="<?php echo $this->lang->line('label_enter_tracker_desc'); ?>" class="mf" cols="" rows=""><?php echo form_text(set_value('description')); ?></textarea>
                    </p>
                  	<p>
                    	<label for="tracker_status"><?php echo $this->lang->line('label_tracker_status'); ?><span style="color:#FF0000;" >*</span></label>
                		<?php
							$options[""] =$this->lang->line('label_tracker_status_select');
                            foreach ($tracker_status as $cobj) { $options[$cobj->value] =$cobj->name; } 
     				 		echo form_dropdown('tracker_status', $options,set_value('tracker_status'),"class='validate[required] sf' alt='".$this->lang->line('label_enter_tracker_status')."'"); 
						?>
                    </p>
					<p>
                    	<label for="conversion_type"><?php echo $this->lang->line('label_tracker_conversion_type'); ?><span style="color:#FF0000;" >*</span></label>
                    	<?php
							$conversion_arr[""] =$this->lang->line('label_tracker_status_select_tracker_conv_type');
                            foreach ($tracker_type as $cobj) { $conversion_arr[$cobj->value] =$cobj->name; } 
     				 		echo form_dropdown('conversion_type', $conversion_arr,set_value('conversion_type'),"class='validate[required] sf' alt='".$this->lang->line('label_enter_tracker_conversion_type')."'"); 
						?>
					</p>
                    
                  <p>
                    	<label for="append_code"><?php echo $this->lang->line('label_tracker_append_code'); ?><span style="color:#FF0000;" >*</span></label>
                        <textarea class="validate[required] mf" name="append_code"  cols="" alt="<?php echo $this->lang->line('label_enter_tracker_append_code'); ?>" rows=""><?php echo form_text(set_value('append_code')); ?></textarea>
                    </p>
                    
                    <p>
                    	<input type="hidden" value="<?php echo $sel_advertiser; ?>" name="sel_advertiser" id="sel_advertiser" />
						<input type="hidden" value="add" name="task" id="task" />
						<button><?php echo $this->lang->line('label_save'); ?></button>
						<button onclick="javascript: goToList();" style="margin-left:10px;" type="button"><?php echo  $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
        </form>
		<script type="text/javascript">
		function goToList(){
					document.location.href='<?php echo site_url("admin/inventory_advertisers/trackers/all/".$sel_advertiser); ?>';
			}
		
		</script>
