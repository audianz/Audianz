<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#add_form").validationEngine();
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/tinymce/jscripts/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,jbimages,wordcount,autosave",
	file_browser_callback : "tinyBrowser",
        // Theme options
        theme_advanced_buttons1 : "jbimages,|link,insertimage,|,code,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
		

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "css/example.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
});
</script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/wysiwyg/jquery.wysiwyg.js"></script>


<script>
<!-- Function which allows only alphabets and spaces
function alphacheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[a-zA-Z\s\-]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>';
						
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
			}s			
			
	<!-- Function which hecks for invalid characters
function metacheck(field, rules, i, options)
		{
				var keyword		=	field.val();
				var alpha			=	/^[0-9a-zA-Z\s]+$/;
				if(!alpha.test(keyword))
					{
						return  '<?php echo $this->lang->line('label_static_page_contains_invalid'); ?>';
						
					}
			}
								
 function typeDupcheck(field, rules, i, options)
        {
                var page_name 	= 	field.val();
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
</script>


<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/excanvas.min.js"></script><![endif]-->

       <?php 
			if($this->session->flashdata('pages_success_message') != ""):
   ?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('pages_success_message'); ?> </p>
					</div>
<?php
			endif;?>
<h1 class="pageTitle"><?php echo $this->lang->line('label_static_pages_page_add_new_page'); ?></h1>			
<form id="add_form" action="<?php echo site_url("admin/pages/insert_page"); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('label_static_pages_page_add_new_page');?></legend>
					<?php echo validation_errors();?>
                    <p>
                    	<label for="page_name"><?php echo $this->lang->line('label_static_pages_page_name:');?>
						<span style="color:red">*</span></label>
                        <input type="text" 
						class="validate[required,funcCall[typeDupcheck],funcCall[uresData],funcCall[charlength],custom[onlyLetterSp]] sf" 
						placeholder="Name" name="page_name" 
						alt="<?php echo $this->lang->line('lang_enter_page_name'); ?>"  
						id="page_name" value="<?php echo form_text(set_value('page_name'));?>"  />
                    	</p>
                    
                    <p>
                    	<label for="status"><?php echo $this->lang->line('label_static_pages_status');?></label>
                         <select name="status" id="status" class="sf">
                          <option value="1" selected="selected"><?php echo $this->lang->line('label_static_page_online');?></option>
                          <option value="0"><?php echo $this->lang->line('label_static_page_offline');?></option>
                        </select>
                    </p>
					
					<p>
                    	<label for="menu_name"><?php echo $this->lang->line('label_page_menu_location');?></label>			
			<select name="menu_name" id="menu_name" class="validate[required,custom[integer]] sf" alt="Please select menu" value="<?php echo form_text(set_value('menu_name'));?>">
			<option value="<?php echo set_select('menu_name', '', TRUE); ?>" ><?php echo $this->lang->line('label_static_page_select_menu');?></option>			
                        <?php 
						
						
						if(($menu_list_parent != FALSE) && count($menu_list_parent) > 0)
						{
							for($i=0;$i<count($menu_list_parent);$i++)
							{ 
							
							$p=ucfirst($menu_list_parent[$i]->menu_name);
							$pid=($menu_list_parent[$i]->id);
								$myval="0";
								for($x=0;$x<count($menu_id);$x++)
								{
									if(($menu_list_parent[$i]->id) == ($menu_id[$x]->menu_id))
									{
										$myval="1";									
									}
										
								}
								?>
							<option value="<?php echo $pid;?>" <?php if($myval == '1') echo 'disabled="disabled"' ?> ><?php echo $p;?></option>
							<?php
							$data=$this->mod_static_pages->get_parent_childs($menu_list_parent[$i]->id);
								if($data != 0)
								{
									for($j=0;$j<count($data);$j++)
									{
										$p="--".ucfirst($data[$j]->menu_name);
										$pid=$data[$j]->id;
										$myval="0";
										for($y=0;$y<count($menu_id);$y++)
											{
												if(($data[$j]->id) == ($menu_id[$y]->menu_id))
												{
													$myval="1";
												}	
											}
												?>
										<option value="<?php echo $pid;?>" <?php if($myval == '1') echo 'disabled="disabled"' ?>><?php echo $p;?></option>
										<?php 
									}
									
								}
							}
						}
						else
						{
							?>
							<option value=""><?php echo "No data found";?></option>
						<?php
						}
						 
					
			?>
					</select>
					
					 
                    </p>
					
					
					<p>
					<label for="meta_keyword"><?php echo $this->lang->line('label_static_page_meta_keyword');?></label>
                        <input type="text" class="validate[funcCall[metacheck]] sf" 
						placeholder="Name" name="meta_keyword" 
						alt="<?php echo $this->lang->line('lang_enter_page_name'); ?>"  
						id="keyword" value="<?php echo form_text(set_value('meta_keyword'));?>"  />
                    </p>
                    
                    <p>
                    	<label for="contents"><?php echo $this->lang->line('label_static_pages_contents');?>
						<span style="color:red">*</span></label>
						<div class="widgetbox inlineblock" style="width:83%">
						<h3 class="" style="-moz-border-radius: 3px 3px 0pt 0pt;">
						<span><?php echo $this->lang->line('label_editor');?></span>
						</h3>
                        <div class="content nopadding">
						<textarea id="wysiwyg"  name="page_content" cols="130" rows="30" class="validate[required]"  >
						<?php echo form_text(set_value('page_content'));?>
						</textarea> 
						</div>
						</div>
                    </p>
                    
                    <p>
                    	<button><?php echo $this->lang->line('label_submit');?></button>
			<button type="button" style="margin-left:10px;" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
						<input type="hidden" id="ucheckdata" name="ucheckdata" />
                    </p>
                    
                </fieldset>
            </div><!--form-->
</form>
<script>
function goToList()
{	
	document.location.href='<?php echo site_url('admin/pages'); ?>';
}
</script>
<script type="text/javascript">
    function selectDropdown(){
        var dropdownValue=document.getElementById("menu_name").value;
        alert(+ dropdownValue);
    }
</script>

