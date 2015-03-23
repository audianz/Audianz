<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#refer_friends").validationEngine();
	});
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "exact",
        elements : "body",
		theme : "advanced",
		
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,jbimages",

        // Theme options
        theme_advanced_buttons1 : "jbimages,|link,insertimage,|,code,save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
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
<h1 class="pageTitle"><?php echo $this->lang->line('label_refer_friends');?></h1>
		<?php 
			if($this->session->flashdata('success_email_message') != ""):
   			?>
					<div class="notification msgsuccess"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('success_email_message'); ?> </p>
					</div>
			<?php
			endif;?>
			
			<?php 
			if($this->session->userdata('error_email') != ""){
			$error_email=$this->session->userdata('error_email');}

			if($this->session->flashdata('error_email_msg') != ""):
   			?>
					<div class="notification msgerror"><a class="close"></a>
  					<p><?php echo $this->session->flashdata('error_email_msg');
					if(count($error_email) == 1)
					{
						echo $error_email[0];
					}
					else
					{
						for($k=0;$k<count($error_email);$k++)
						{	
							echo $error_email[$k].',';
						}
					}
					?> </p>
					</div>
			<?php
			endif;?>
	 
	 	
	   
	   <form id="refer_friends" name="refer_friends" action="<?php echo site_url("advertiser/refer_friends/send_email"); ?>" method="post">
        
        	<div class="form_default">
                <fieldset>
            <legend><?php echo $this->lang->line('label_refer_friends'); ?></legend>
		<?php echo validation_errors();?>
			
                    <p>
                    	<label for="from"><?php echo $this->lang->line('label_refer_friends_from'); ?></label>
                        <input type="text"  class="from" size="35" placeholder="Name" name="from" id="from" value="<?php echo $email; ?>" disabled="disabled"  />
                    	</p>
                    
                    <p>
                    	<label for="to"><?php echo $this->lang->line('label_refer_friends_to'); ?><span style="color:red">*</span>:</label>
                        <textarea class="validate[required] sf" alt="<?php echo $this->lang->line('label_enter_send_email_address'); ?>" name="to" id="to" cols="25" rows="3" value="<?php echo form_text(set_value('to'));?>"></textarea>
                    </p>
					
					<p>
                    	<label for="subject"><?php echo $this->lang->line('label_refer_friends_subject'); ?>:</label>
                        <input type="text" disabled="disabled" name="subject" id="subject" size="35" value="<?php echo $this->lang->line('label_refer_friends_subject_content').$this->lang->line('site_title'); ?>" />
                    </p>
					
			<p>
                   	<label for="body"><?php echo $this->lang->line('label_refer_friends_body'); ?><span style="color:red">*</span>:</label>
						
					<textarea id="body" class="validate[required] sf" alt="<?php echo $this->lang->line('label_enter_body_content'); ?>" name="body_content" cols="80" rows="20">
						<p align="left">
						<?php echo $this->lang->line('label_refer_friends_refer_body_content1'); ?><br /><br />
						<?php echo $this->lang->line('label_refer_friends_refer_body_content2'); ?><br /><br />
						<?php echo $this->lang->line('label_refer_friends_refer_body_content3'); ?><br /><br />
						</p>
						<p align="right">
						<?php echo $this->lang->line('label_refer_friends_refer_body_content4'); ?><br />	
						<?php echo $name;?>			
						</p>						
					</textarea>
                    </p>

                    <p><button><?php echo $this->lang->line('label_refer_friends_send'); ?></button></p>
					
                </fieldset>
            </div><!--form-->

        </form>
