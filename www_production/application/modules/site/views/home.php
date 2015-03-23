<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.jgrowl.js"></script>
<script>

jQuery(window).load(function(){
        check_session();
         });
         
function check_session()
        {                
                        jQuery.ajax({
                        url:'./site/login_check',
                                success: function(msg) 
                                {
                                        if(msg!='')
                                          {
                                             window.location = msg;
                                                 }
                                 }
                        });        
                                
         }
	jQuery(document).ready(function(){
		
		jQuery('#username').focus();
              
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


	function test(){
		alert("test");
			//document.location.href=url;
	}


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
                                        location.reload();
					var msg = "<?php echo $this->lang->line('label_user_emailid_information'); ?>";
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
jQuery('#username').focus();
 if(publisher_user =='')
 
 {  
    document.getElementById("username").value ='';
    document.getElementById("password").value ='';  
   }
  else
  { 
jQuery('#username').val(jQuery.cookie('cookie_dreamads_publisher_user'));
jQuery('#password').val(jQuery.cookie('cookie_dreamads_publisher_user_pwd'));

  }
}


else if(type=='ADVERTISER')
{
jQuery('#username').focus();
 if(advertise_user =='')
 {  
    document.getElementById("username").value ='';
    document.getElementById("password").value ='';  
   }
  else
  {
jQuery('#username').val(jQuery.cookie('cookie_dreamads_advertiser_user'));
jQuery('#password').val(jQuery.cookie('cookie_dreamads_advertiser_user_pwd'));
  }
}

else
{
}
}

function enterScript(e){

if (e.keyCode == 13) {
        
        cookie_chk();
        
    }

}

function enterEmail(e){

if (e.keyCode == 13) {
        
        cookie_chk();
        
    }

}

</script>
<div id="slide" style="background:#EEE;padding-bottom:10px;" align="left">
<a href="<?php echo site_url('site');?>"><img src="<?php echo base_url(); ?>assets/images/banner.jpg" alt="Logo" /></a>
	</div>
	<div class="two_third maincontent_inner ">
    	<div class="left">
			<h1><?php echo $page['page_title']; ?></h1>
			<br/>
			<p><?php echo $page['description']; ?></p>
			<br/>                        
        </div><!-- left -->            
		
    </div><!-- two_third -->
    
    <div class="one_third last">
    
      <div class="right">
        
      <div class="widgetbox">
      <h3><span><?php echo $this->lang->line('label_site_login');?></span></h3>
      <div class="sitecontent">
        <div id="loginpanel">
          <div class="signinbox" style="height:320px;">
            <div class="signinbox_inner">
              <div class="signinbox_content" style="height:280px;" > <span id="sec1" >
                <form id="loginform" name ="loginform" action="<?php echo site_url('login/login_process_ajax'); ?>" method="post" >
                  <input type="text" name="username" id="username" autocomplete="off" class="username"   alt="<?php echo $this->lang->line('label_admin_login_username');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_advertiser_user'])!='')?$_COOKIE['cookie_dreamads_advertiser_user']:''; ?>" onkeypress="return enterScript(event)" />
                  <input type="password" name="password" id="password" autocomplete="off" class=" password"   alt="<?php echo $this->lang->line('label_admin_login_password');?>" value="<?php echo (isset($_COOKIE['cookie_dreamads_advertiser_user_pwd'])!='')?$_COOKIE['cookie_dreamads_advertiser_user_pwd']:''; ?>" onkeypress="return enterScript(event)"  />
                  <input type="radio" name="checkbox_type" id="checkbox_type" checked="checked"  onchange="type_chk(this.value)"       value="ADVERTISER" style="margin-left:10px;"/>
                  <font style=" margin-right:10px; font-size:12px;"><?php echo $this->lang->line('label_login_advertise');?></font>
                  <input type="radio" name="checkbox_type"  id="checkbox_type1"  onchange="type_chk(this.value)"      value="TRAFFICKER" style="margin-left:25px;" />
                  <font style=" margin-right:50px;font-size:12px;vertical-align:middle;"><?php echo $this->lang->line('label_login_publisher');?></font>
                  <button type="button" name="submit" style="margin-top:14px;" onclick="cookie_chk()" class="submit"  ><?php echo $this->lang->line('label_admin_login_image');?></button>
                  <div class="quickloginoption" style="width:100%;">
                    <div style="float:left;width:60s%;"><a href="javascript: forget_password()";  class="cant"><?php echo $this->lang->line('label_admin_login_forget_password');?></a></div>
                    <div style="float:right;">
                      <input type="checkbox" name="remember" id="remember" style="margin-right:5px" value="1" <?php echo (isset($_COOKIE['cookie_dreamads_publisher_user']) && isset($_COOKIE['cookie_dreamads_publisher_user_pwd']))?'checked':'';?>/>
                      <?php echo $this->lang->line('label_site_stay_signed_in');?></div>
<br/>
<br/>
<?php /* ?><div align="center" style="float:right;color:#FFF;font-weight:bold;font-size:12px;width:100%;">
Contact us for Live Demo:
<br/>
</div>
<div align="center" >
<a style="font-size:14px;float:left;color:#224E82;padding-left:55px;" href="mailto:info@openxservices.com">info@openxservices.com</a>
</div><?php */ ?>
              
 </div>
                </form>
                </span> <span id="sec2">
                <form id="loginform" action="" method="post">
                  <input type="text" name="useremail" id="email" autocomplete="off" class="email"  onkeypress="return enterEmail(event)"  /><br>
                  <input type="radio" name="checkbox_type_forget" id="checkbox_type_forget1" checked="checked"     value="ADVERTISER" style="margin-left:5px;"/>
                  <font style=" margin-right:10px; font-size:12px;"><?php echo $this->lang->line('label_login_advertise');?></font>
                  <input type="radio" name="checkbox_type_forget"  id="checkbox_type_forget2"      value="TRAFFICKER" style="margin-left:5px;" />
                  <font style=" margin-right:40px;font-size:12px;vertical-align:middle;"><?php echo $this->lang->line('label_login_publisher');?></font> <br/>
                  <br/>
                  <br/>
                  <!--  -->
                  <button type="button"  onclick="email_chk()" name="submit" class="submit"   style="margin-left:80px" ><?php echo $this->lang->line('label_admin_login_submit');?></button>
                  <br/>
                  <br/>
                  <br/>
                </form>
                </span> </div>
              <!--loginbox_content-->
            </div>
            <!--loginbox_inner-->
          </div>
          <!--loginbox-->
        </div>
        <!-- Login Panel -->
      </div>
      <!-- content -->
    </div>
    <!-- widgetbox -->
            
            
            
            
            
    	</div><!--right-->
    </div><!--one_third last-->
    
    <br clear="all" />	

