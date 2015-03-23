<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#mobile_screens").validationEngine();
	});
	
	/**
	*
	* @param {jqObject} the field where the validation applies
	* @param {Array[String]} validation rules for this field
	* @param {int} rule index
	* @param {Map} form options
	* @return an error string if validation failed
	*/
</script>
 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?> 
		<?php  
						if($this->session->userdata('notification_message') !=''):
					?>
					<div class="notification msgerror">
						<a class="close"></a>
						<p>
					 <?php 
								
								echo $this->session->userdata('notification_message');
								$this->session->unset_userdata('notification_message');							
					 ?>
				</p>
			</div>		
	<?php 
		endif;
	?>
					<?php  
						if($this->session->userdata('message') !=''):
					?>
					<div class="notification msgsuccess">
						<a class="close"></a>
						<p>
					 <?php 
								
								echo $this->session->userdata('message');
								$this->session->unset_userdata('message');							
					 ?>
				</p>
			</div>		
	<?php 
		endif;
	?>

   <br />

     <?php
		$i=0;
		foreach ($mobile_screen_list as $obj):
			$width[$i]  =$obj->width;
			$height[$i] =$obj->height;
			$i++;
		endforeach;
	?>   

		<form id="mobile_screens" action="<?php echo site_url('admin/settings_mobile_screens/update_process'); ?>" method="post">

       <?php  //print_r($mobile_screen_list);?>

        	<div class="form_default">

                <fieldset>

                    <legend><?php echo $page_title; ?></legend>
					
				
					
					
                    <div class="mobilewidth">

                    <p>

                    	<label for="master_1"><?php echo $this->lang->line("label_mobile_zone_size"); ?> 1 (<?php echo $this->lang->line("label_width"); ?> x <?php echo $this->lang->line("label_height"); ?>) <span class="mandatory">*</span></label>

                        <input type="text" name="master1_width"  id="master1_width" class="validate[required,custom[onlyNumberSp]] s_text" size="5"/ value="<?php echo(form_text(set_value('master1_width') != '')?set_value('master1_width'):$width[0]);?>" alt="<?php echo $this->lang->line('notification_enter_master_width'); ?>"/>

                        <input type="text" name="master1_height"  id="master1_height" class="validate[required,custom[onlyNumberSp]] s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$height[0]);?>" alt="<?php echo $this->lang->line('notification_enter_master_height'); ?>"/>

                    </p>

                   <p>

                    	<label for="child_1"><?php echo $this->lang->line("label_mobile_zone_size"); ?> 2 (<?php echo $this->lang->line("label_width"); ?> x <?php echo $this->lang->line("label_height"); ?>) <span class="mandatory">*</span></label>

                        <input type="text" name="child1_width"  id="child1_width" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$width[1]);?>" alt="<?php echo $this->lang->line('notification_enter_child1_width'); ?>" />

                        <input type="text" name="child1_height"  id="child1_height" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$height[1]);?>" alt="<?php echo $this->lang->line('notification_enter_child1_height'); ?>"/>

                    </p>

                   

                   <p>

                    	<label for="child_2"><?php echo $this->lang->line("label_mobile_zone_size"); ?> 3 (<?php echo $this->lang->line("label_width"); ?> x <?php echo $this->lang->line("label_height"); ?>) <span class="mandatory">*</span></label>

                        <input type="text" name="child2_width"  id="child2_width" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$width[2]);?>" alt="<?php echo $this->lang->line('notification_enter_child2_width'); ?>"/>

                        <input type="text" name="child2_height"  id="child2_height" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$height[2]);?>" alt="<?php echo $this->lang->line('notification_enter_child2_height'); ?>"/>

                    </p>

                     <p>

                    	<label for="child_3"><?php echo $this->lang->line("label_mobile_zone_size"); ?> 4 (<?php echo $this->lang->line("label_width"); ?> x <?php echo $this->lang->line("label_height"); ?>) <span class="mandatory">*</span></label>

                        <input type="text" name="child3_width"  id="child3_width" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$width[3]);?>" alt="<?php echo $this->lang->line('notification_enter_child3_width'); ?>"/>

                        <input type="text" name="child3_height"  id="child3_height" class="s_text" size="5" value="<?php echo form_text((set_value('master1_width') != '')?set_value('master1_width'):$height[3]);?>" alt="<?php echo $this->lang->line('notification_enter_child3_height'); ?>"/>

                    </p>

                      

                    

                    

                    <p>

                    	<button type ="button" onclick="confirm_update();"><?php echo $this->lang->line('label_update'); ?></button>
                    	<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>

                    </p>

                    </div>

                </fieldset>

            </div><!--form-->
</form>

<script>

function goToList()
{	
	document.location.href='<?php echo site_url('admin/dashboard'); ?>';
}

function confirm_update()
{
	//jQuery('#mobile_screens').submit();
	/* Database original sizes*/
	var s1 = '<?php echo $width[0].'X'.$height[0]; ?>';
	var s2 = '<?php echo $width[1].'X'.$height[1]; ?>';
	var s3 = '<?php echo $width[2].'X'.$height[2]; ?>';
	var s4 = '<?php echo $width[3].'X'.$height[3]; ?>';
	
	var master_width = document.getElementById("master1_width").value;
	var master_height = document.getElementById("master1_height").value;
	var child1_width = document.getElementById("child1_width").value;
	var child1_height = document.getElementById("child1_height").value;
	var child2_width = document.getElementById("child2_width").value;
	var child2_height = document.getElementById("child2_height").value;
	var child3_width = document.getElementById("child3_width").value;
	var child3_height = document.getElementById("child3_height").value;
	
	var master = master_width+'X'+master_height;
	var child1 = child1_width+'X'+child1_height;
	var child2 = child2_width+'X'+child2_height;
	var child3 = child3_width+'X'+child3_height;
		
	if(master!=s1 || child1!=s2 || child2!=s3 || child3!=s4)
	{
		jConfirm('<?php echo $this->lang->line("Edit_Size_Alert"); ?>', '<?php echo $this->lang->line("Edit_Size_Alert_Title"); ?>', function(r){
			if(r)
			{
				document.getElementById("mobile_screens").submit();
				return true;        
			}
			else
			{
				return false;
			}
		});
		return false;
	}
	else
	{
		document.getElementById("mobile_screens").submit();
	}
	
}
</script>

        
