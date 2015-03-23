<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
<script>
	jQuery(document).ready(function(){
              
            if(document.getElementById("username").value !="" && document.getElementById("password").value !="" )

		{ 
                 jQuery('.username, .password').css({backgroundPosition: "0 -32px"});
                }
             else
                {
                 jQuery('.username, .password').css({backgroundPosition: "0 0"});
		}
		
	   		jQuery('#sec1').show();
		    jQuery('#sec2').hide();
	         
	});		
	
	jQuery('.growl2').click(function(){
		var msg = "welcome";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
		return false;
	});
	
	//this will prevent growl box to show on top of the header when
	//scroll event is fired
	jQuery(document).scroll(function(){
		if(jQuery('.jGrowl').length != 0) {
			var pos = jQuery(document).scrollTop();
			if(pos < 50) jQuery('.jGrowl').css({top: '100px'}); else jQuery('.jGrowl').css({top: '0'});
		}
	});

	

	</script>
<script>
function cookie_chk()
{

  if( document.getElementById("username").value == '')

{
	  var msg = "<?php echo  $this->lang->line('label_admin_login_username');?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
		
}

if( document.getElementById("password").value == '')

{
	  var msg = "<?php echo  $this->lang->line('label_admin_login_password');?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
		
}					 
        	var username = document.getElementById("username").value;
			var password = document.getElementById("password").value;
			var remember = document.getElementById("remember").value;
			//alert("test")
//alert(document.getElementsByName("checkbox_type").length);
			for (var i=0; i < document.getElementsByName("checkbox_type").length; i++)
   			{
  					 if (document.getElementsByName("checkbox_type")[i].checked)
      					{
      						var checkbox_type = document.getElementsByName("checkbox_type")[i].value;
      					}
   			}
			//alert(checkbox_type);
		
			var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("login/login/login_process_ajax"); ?>",
			data: "username="+username+"&password="+password +"&remember="+remember+"&checkbox_type="+checkbox_type,
    			success : function (data) {
				
				if(data == 'yes')
				{
	    var msg = "<?php echo  $this->lang->line('label_invalid_user_password');?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
				}
				else if(data =='1')
				{
					
					 window.location=' <?php echo  site_url("publisher/dashboard")?>';
				}
				else if(data =='2')
				{
					window.location=' <?php echo site_url("advertiser/dashboard")?>';
				}
				else 
				{
					
				}
			}
		}); 
		 
}

function forget_password()
{
           
			jQuery('#sec1').hide();
	       jQuery('#sec2').show();
}

function email_chk()
{
 var email = document.getElementById("email").value;
 var mailadd = email.split("@");
      
    
		if(mailadd.length>1)
		{
		  
			var finalsplit = mailadd[1].split(".");
			if(finalsplit.length <= 1)
			{
		   var msg = "<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
			}
			
			else{
				if(finalsplit[1]=='' || finalsplit[1]==null)
				{
		 var msg = "<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
				}
			}
		}
		else
		{
				
		 var msg = "<?php echo $this->lang->line('lang_advertiser_enter_valid_email'); ?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
		}
					 
        	var email = document.getElementById("email").value;
		
			//alert("test");
			for (var i=0; i < document.getElementsByName("checkbox_type_forget").length; i++)
   			{
  					 if (document.getElementsByName("checkbox_type_forget")[i].checked)
      					{
      						var checkbox_type = document.getElementsByName("checkbox_type_forget")[i].value;
      					}
   			}
			//var checkbox_type= document.getElementsByName("checkbox_type")[1].value;
		//alert(checkbox_type);
			var flag = 1;
		jQuery.ajax({ cache: false,

			type : "POST",

			url: "<?php echo site_url("login/login/forget_password_process_ajax"); ?>",
			data: "email="+email+"&checkbox_type="+ checkbox_type,
    			success : function (data) {
				
				if(data =='yes')
				{
	    var msg = "<?php echo $this->lang->line('invalid_user_emailid');?>";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
				}
				else if (data =='N0')
				{
					var msg = "<?php echo  $this->lang->line('lang_forget_password_information');?>";
					var position = "top-right";
					var scrollpos = jQuery(document).scrollTop();
					if(scrollpos < 50) position = "customtop-right";
					jQuery.jGrowl(msg, { life: 5000, position: position});
				}
			  else
			         {
					 
					 }
			}
		}); 
		 
}


function viewAllCookie() {
  //  var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
       /* var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);*/
		alert(ca[i]);
    }
    return null;
}

type_chk('ADVERTISER');

function type_chk(type)
{

var advertise_user  = (jQuery.cookie('cookie_dreamads_advertiser_user') !='') ?jQuery.cookie('cookie_dreamads_advertiser_user'): '';
var advertiser_password  =(jQuery.cookie('cookie_dreamads_advertiser_user_pwd') !='') ?jQuery.cookie('cookie_dreamads_advertiser_user_pwd'): '';
var publisher_user  = (jQuery.cookie('cookie_dreamads_publisher_user') !='') ?jQuery.cookie('cookie_dreamads_publisher_user'): '';
var publisher_password =(jQuery.cookie('cookie_dreamads_publisher_user_pwd') !='') ?jQuery.cookie('cookie_dreamads_publisher_user_pwd'): '';

if(type=='TRAFFICKER')
{       

 if(publisher_user ==''  &&  advertise_user !='')
 
 {  
    document.getElementById("username").value ='';
    document.getElementById("password").value ='';  
   }
  else
  { 
 document.getElementById("username").value = (jQuery.cookie('cookie_dreamads_publisher_user'));
 document.getElementById("password").value =(jQuery.cookie('cookie_dreamads_publisher_user_pwd')); 
  }
}


else if(type=='ADVERTISER')
{

 if(advertise_user =='')
 {  
    document.getElementById("username").value ='';
    document.getElementById("password").value ='';  
   }
  else
  {
 document.getElementById("username").value = jQuery.cookie('cookie_dreamads_advertiser_user');

 document.getElementById("password").value = jQuery.cookie('cookie_dreamads_advertiser_user_pwd');
  }
}

else
{
}
}
</script>
<div id="slide" style="background:#EEE;padding-bottom:10px;" align="center">
		<img src="<?php echo base_url(); ?>assets/images/banner.jpg" alt="Logo" /></a>
	</div>
	<div class="two_third maincontent_inner ">
    	<div class="left">
			<h1><?php echo $page['page_title']; ?></h1>
			<br/>
			<p><?php echo $page['description']; ?></p>
			<br/>                        
        </div><!-- left -->            
		
    </div><!-- two_third -->
    
   <?php echo $this->input->cookie();?>
 
    <div class="one_third last">
    
    	<div class="right">
        
			<div class="widgetbox">
            	<h3><span>Login</span></h3>
                <div class="sitecontent">
						<div id="loginpanel">
			<div class="signinbox">

				<div class="signinbox_inner">

				<div class="signinbox_content">
				 <span id="sec1">
                               
				 <form id="loginform" action="<?php echo site_url('login/login_process_ajax'); ?>" method="post" >
                   <input type="text" name="username" id="username" autocomplete="off" class="username"   alt="<?php echo $this->lang->line('label_admin_login_username');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_advertiser_user'])!='')?$_COOKIE['cookie_dreamads_advertiser_user']:''; ?>" />
					
					<input type="password" name="password" id="password" autocomplete="off" class=" password"   alt="<?php echo $this->lang->line('label_admin_login_password');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_advertiser_user_pwd'])!='')?$_COOKIE['cookie_dreamads_advertiser_user_pwd']:''; ?>"   />
					<input type="radio" name="checkbox_type" id="checkbox_type" checked="checked"  onchange="type_chk(this.value)"       value="ADVERTISER" style="margin-left:10px;"/> <font style=" margin-right:10px; font-size:12px;"><?php echo $this->lang->line('label_login_advertise');?></font>
              <input type="radio" name="checkbox_type"  id="checkbox_type1"  onchange="type_chk(this.value)"      value="TRAFFICKER" style="margin-left:25px;" /> <font style=" margin-right:50px;font-size:12px;vertical-align:middle;"><?php echo $this->lang->line('label_login_publisher');?></font>
										<button type="button" name="submit" onclick="cookie_chk()" class="submit"  ><?php echo $this->lang->line('label_admin_login_image');?></button>
					<div class="quickloginoption" style="width:100%;">
					<div style="float:left;width:60s%;"><a href="javascript: forget_password()";  class="cant"><?php echo $this->lang->line('label_admin_login_forget_password');?></a></div>
					<div style="float:right;"><input type="checkbox" name="remember" id="remember" style="margin-right:5px" value="1" <?php echo (isset($_COOKIE['cookie_dreamads_publisher_user']) && isset($_COOKIE['cookie_dreamads_publisher_user_pwd']))?'checked':'';?>/>Stay signed in</div>
					</div>
					  </form>		
					</span>
		<span id="sec2">
						<form id="loginform" action="" method="post">
						 
						 <input type="text" name="email" id="email" autocomplete="off" class="username"   />
					
				<input type="radio" name="checkbox_type_forget" id="checkbox_type_forget1" checked="checked"     value="ADVERTISER" style="margin-left:5px;"/> <font style=" margin-right:10px; font-size:12px;"><?php echo $this->lang->line('label_login_advertise');?></font>
              <input type="radio" name="checkbox_type_forget"  id="checkbox_type_forget2"      value="TRAFFICKER" style="margin-left:5px;" /> <font style=" margin-right:40px;font-size:12px;vertical-align:middle;"><?php echo $this->lang->line('label_login_publisher');?></font>
			  <br/>
			<br/>
			<br/> <!--  -->
			<button type="button"  onclick="email_chk()" name="submit" class="submit"   style="margin-left:80px" ><?php echo $this->lang->line('label_admin_login_submit');?></button>
			<br/>
			<br/>
			<br/>
			</form>
			</span>
					
				</div><!--loginbox_content-->

				</div><!--loginbox_inner-->

				</div><!--loginbox-->
		</div><!-- Login Panel -->
                </div><!-- content -->
            </div><!-- widgetbox -->
            <div class="widgetbox">
            	<h3><span>Login</span></h3>
                <div class="content">
                	
                    <label>Username</label><br/>
                    <input type="text" size="25" />
                    <br/>
                    <label>Password</label><br/>
                    <input type="text"  size="25" />
                    <br/>
                    <br/>
                    <input type="submit" value="Submit" />
                    <input type="reset" value="Clear" />
                    
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <div class="widgetbox">
            	<h3><span>List</span></h3>
                <div class="content" style="padding-left:30px;">
					<ol>
						<li>Raja</li>
						<li>Pooja</li>
						<li>Rocky</li>
						<li>Sharmila</li>
					</ol>
                </div><!-- content -->
            </div><!-- widgetbox -->
            
            <div class="widgetbox">
            	<h3><span>Test</span></h3>
                <div class="content">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                </div><!-- content -->
            </div><!-- widgetbox -->
            
    	</div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />


