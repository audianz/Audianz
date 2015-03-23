<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_add_tracker").validationEngine();
	});
</script>
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
                        <input value="<?php echo form_text((set_value('tracker_name') != '')?set_value('tracker_name'):$tracker_det->trackername); ?>" type="text" alt="<?php echo $this->lang->line('label_enter_tracker_name'); ?>" name="tracker_name"  id="tracker_name" class="validate[required] sf" />
                    </p>
                     <p>
                    	<label for="description"><?php echo $this->lang->line('label_tracker_desc'); ?></label>
                        <textarea name="description" alt="<?php echo $this->lang->line('label_enter_tracker_desc'); ?>" class="mf" cols="" rows=""><?php echo form_text((set_value('description') != '')?set_value('description'):$tracker_det->description); ?></textarea>
                    </p>
                  	<p>
                    	<label for="tracker_status"><?php echo $this->lang->line('label_tracker_status'); ?><span style="color:#FF0000;" >*</span></label>
                		<?php
							$options[""] =$this->lang->line('label_tracker_status_select');
                            foreach ($tracker_status as $cobj) { $options[$cobj->value] =$cobj->name; } 
     				 		echo form_dropdown('tracker_status', $options,((set_value('tracker_status')!= '')?set_value('tracker_status'):$tracker_det->status)," class='validate[required] sf' alt='".$this->lang->line('label_enter_tracker_status')."'"); 
						?>
                    </p>
					<p>
                    	<label for="conversion_type"><?php echo $this->lang->line('label_tracker_conversion_type'); ?><span style="color:#FF0000;" >*</span></label>
                    	<?php
							$conversion_arr[""] =$this->lang->line('label_tracker_status_select_tracker_conv_type');
                            foreach ($tracker_type as $cobj) { $conversion_arr[$cobj->value] =$cobj->name; } 
							$sel_value = (set_value('conversion_type')!= '')?set_value('conversion_type'):$tracker_det->type;
     				 		echo form_dropdown('conversion_type', $conversion_arr,$sel_value,"class='validate[required] sf' alt='".$this->lang->line('label_enter_tracker_conversion_type')."'"); 
						?>
					</p>
                    
                  <p>
                    	<label for="append_code"><?php echo $this->lang->line('label_tracker_append_code'); ?><span style="color:#FF0000;" >*</span></label>
                        <textarea class="validate[required] mf" name="append_code"  cols="" alt="<?php echo $this->lang->line('label_enter_tracker_append_code'); ?>" rows=""><?php echo form_text((set_value('append_code')!= '')?set_value('append_code'):$tracker_det->appendcode); ?></textarea>
                    </p>
                    
                    <p>
                    	<input type="hidden" value="<?php echo $sel_tracker_id; ?>" name="sel_tracker_id" id="sel_tracker_id" />
						<input type="hidden" value="<?php echo $tracker_det->clientid; ?>" name="sel_advertiser" id="sel_advertiser" />
						<input type="hidden" value="edit" name="task" id="task" />
						<button><?php echo $this->lang->line('label_save'); ?></button>
						<button onclick="javascript: goToList();" style="margin-left:10px;" type="button"><?php echo  $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
        </form>
		<script type="text/javascript">
		function goToList(){
					document.location.href='<?php echo site_url("admin/inventory_advertisers/trackers/all/".$tracker_det->clientid); ?>';
			}
		
		</script>
