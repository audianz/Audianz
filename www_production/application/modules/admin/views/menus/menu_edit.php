<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#edit_menu_form").validationEngine();
	});
	
	<!-- Function which allows only alphabets and spaces-->
	function alphacheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[a-zA-Z\s]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('label_static_page_only_alpha'); ?>';
						
					}
			}
			
	<!-- Function which checks for character length-->
	function charlength(field, rules, i, options)
		{
				var keyword		=	field.val();
				if((keyword.length)>20||(keyword.length)<3)
					{
						return  '<?php echo $this->lang->line('lang_static_page_char_menu'); ?>';
						
					}
			}		
			
	<!-- Function to Check menu name duplication-->		
	 function typeDupcheck(field, rules, i, options)
        {
                var menu_name 	= 	field.val();
				var flag = 1;
                jQuery.ajax({ cache: false,

                        type : "POST",

                        url: "<?php echo site_url("admin/pages/page_name_check"); ?>",
                        data: "page_name="+page_name,

                        success : function (data) {
						
                                if(data == '1')
                                {
								
                                        document.getElementById('ucheckdata').value = data;
                                }
                                else
                                {
                                        document.getElementById('ucheckdata').value = data;
                                }
                        }
                });
                                
        }
        
        function uresData(field, rules, i, options)
        {
                var storeData = document.getElementById('ucheckdata').value;
                if(storeData == 'yes')
                {
                        return '<?php echo $this->lang->line('label_page_name_exists'); ?>';
                }
        }
        function menu_chars_check(field, rules, i, options)
		{
			if(field.val() != '')
			{
				var keyword		=	field.val();
				var alpha			=	/^[a-zA-Z0-9_.]+$/;
				if(!alpha.test(keyword))
				{
					return  '<?php echo "Please enter alphabets, numbers, underscores, fullstops only"; ?>';
					
				}
			}
		}					
</script>
<h1 class="pageTitle"><?php echo $this->lang->line('lang_static_page_edit_menu');?></h1>
<form action="<?php echo site_url("admin/menus/update_menu");?>" method="post" id="edit_menu_form" name="edit_menu_name">
        	<?php foreach($record as $row):?>
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('lang_static_page_edit_menu');?></legend>
                   <?php echo validation_errors();?>
				   
				   <p>
                    <label for="Parent_menu"><?php echo $this->lang->line('lang_static_page_sub_menu_name');?></label> 
						<?php
							$sel_menu = (set_value('parent_menu') != '')?set_value('parent_menu'):$row->parent_id;
							
							if($row->parent_id=='0')
							{
								$options[""]	=$this->lang->line('label_static_page_parent_menu');
							}
							else
							{
								foreach ($menu as $item) {$options[$item->id] =ucfirst($item->menu_name);}
							}
							echo form_dropdown('parent_menu', $options, $sel_menu, "class='sf' disabled='disabled' "); ?>
                    </p>	
                    <p>
                    	<label for="name"><?php echo $this->lang->line('lang_static_page_menu_name');?><span style="color:red">*</span></label>
                        <input type="text" name="menu_name"  id="name" class="validate[required,funcCall[charlength]] sf"
						value="<?php echo form_text((set_value('menu_name') !='')?set_value('menu_name'): $row->menu_name); ?>"
						 alt="<?php echo $this->lang->line('lang_static_page_enter_menu_name');?>" maxlength="20" />
						 <input type="hidden" name="menu_id" value="<?php echo $row->id;?>"  />
                    </p>
					<p>
                    	<button><?php echo $this->lang->line('label_save');?></button>
                    	 <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            <?php endforeach;?>
        
        </form>       
<script>
function goToList()
{	
	document.location.href='<?php echo site_url('admin/menus'); ?>';
}
</script>