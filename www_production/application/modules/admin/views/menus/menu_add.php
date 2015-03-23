<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#menu_add_form").validationEngine();
	});
</script>
<script>

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
<!-- Function to Check menu name duplication-->						
function typeDupcheck(field, rules, i, options)
        {
                var menu 	= 	field.val();
				
				var flag = 1;
                jQuery.ajax({ cache: false,

                        type : "POST",

                        url: "<?php echo site_url("admin/menus/page_name_check"); ?>",
                        data: "menu="+menu,

                        success : function (data) {
							
                                if(data == 'yes')
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
        
<!-- Function which checks for character length-->
	function charlength(field, rules, i, options)
		{
				var keyword		=	field.val();
				if((keyword.length)>20||(keyword.length)<3)
					{
						return  '<?php echo $this->lang->line('lang_static_page_char_menu'); ?>';
						
					}
			}        


function uresData(field, rules, i, options)
        {
              
				var storeData = document.getElementById('ucheckdata').value;
				
				
                if(storeData == 'yes')
                {
                        return '<?php echo $this->lang->line('lang_static_page_menu_exists_already'); ?>';
                }
        }					
		
	</script>
<h1 class="pageTitle"><?php echo $fieldset; ?></h1>
<form id="menu_add_form" action="<?php echo site_url("admin/menus/insert_menu")?>" method="post">
     <div class="form_default">
                
		<fieldset>
		   
			 <legend><?php echo $fieldset;?></legend>
             <?php echo validation_errors();?>
			 <p>
                    <label for="Sub_menu"><?php echo $this->lang->line('lang_static_page_sub_menu_name');?></label> 
					<?php
					$options[""] =$this->lang->line('label_static_page_parent_menu');
					if($menu_name != FALSE)
					{
						foreach ($menu_name as $names) 
						{
							 $options[$names->id] =ucfirst($names->menu_name);
						} 
					}?>
					<?php echo form_dropdown('parent_menu_id', $options,set_value('parent_menu_id'),"class='sf' "); ?>
              </p>
			 
			  <p>
                    <label for="name"><?php echo $this->lang->line('lang_static_page_menu_name');?> 
					<span style="color:red">*</span></label>
                     <input type="text" name="menu_name"  id="name" maxlength="20"
					 class="validate[required,funcCall[typeDupcheck],funcCall[uresData],funcCall[charlength]] sf" 
					 value="<?php echo form_text(set_value('menu_name'));?>"
					 alt="<?php echo $this->lang->line('lang_static_page_enter_menu_name');?>"/>
              </p>
			  
			  
			  
			  <p>
                    <button><?php echo $this->lang->line('label_submit');?></button>
                    <button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
					<input type="hidden" id="ucheckdata" name="ucheckdata" />
               </p>
                    
           </fieldset>
     </div>
</form>       
<script>
function goToList()
{	
	document.location.href='<?php echo site_url('admin/menus'); ?>';
}
</script>