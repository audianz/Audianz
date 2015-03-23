<?php			
$total_notify_approval		=	$this->mod_notifications->get_notify('Approval');
			
$total_notify_today	=	$this->mod_notifications->get_notify('Today');
?>

<div class="topheader">
        <ul class="notebutton">
		<?php if($this->session->userdata('session_user_type') =='ADVERTISER') { ?>
            <li class="note">
                <a href="javascript:void(0);" onclick="message_today('adv');" title="<?php echo $this->lang->line('label_message_notification_title');?>">
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
						<?php $count	=$this->mod_suggestions->get_tabread('0', $this->session->userdata('session_advertiser_id')); ?>
						<?php if($count >0) : ?><span class="count"><?php echo $count; ?></span><?php endif; ?>
                    </span>
                </a>
		<div class="message_adv"></div>
            </li>
			<?php } else if($this->session->userdata('session_user_type') =='TRAFFICKER') { ?>
			 <li class="note">
                <a href="javascript:void(0);" onclick="message_today('pub');" >
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
						<?php $count	=$this->mod_suggestions->get_tabread('0', $this->session->userdata('session_publisher_id')); ?>
						<?php if($count >0) : ?><span class="count"><?php echo $count; ?></span><?php endif; ?>
                    </span>
                </a>
		<div class="message_pub"></div>
            </li>
			<?php } else { ?>
            <li class="note">
                <a href="javascript:void(0);" onclick="message_today('admin');" title="<?php echo $this->lang->line('label_message_notification_title');?>">
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
						<?php $count	=$this->mod_suggestions->get_tabread('0', $this->session->userdata('mads_sess_admin_id')); ?>
						<?php if($count >0) : ?><span class="count"><?php echo $count; ?></span><?php endif; ?>
                    </span>
                </a>
		<div class="message_admin"></div>
            </li>
			<?php } ?>
           <?php if($total_notify_approval>0 && isset($total_notify_approval)):?>
            <li class="note">
				
            	<a href="javascript:void(0);" onclick="approve_notify();" title="<?php echo $this->lang->line('lang_notify_approval_notify');?>">
                	<span class="wrap">
                    	<span class="thicon infoicon"></span>
                        <span class="count"><?php echo $total_notify_approval ;?></span>
				
                    </span>
                </a>
		<div class="approve_notify"></div>
		    </li>
			<?php endif; ?>
			<?php if($total_notify_today>0 && isset($total_notify_today)):?>
			<li class="note">
            	<a href="javascript:void(0);" onclick="notify_today();" title="<?php echo $this->lang->line('lang_notify_today_notify_title');?>">
	        	<span class="wrap">
	            	<span class="thicon infoicon"></span>
			<span class="count"><?php echo $total_notify_today;?></span>
	            	</span>
                </a>
		<div class="notify_today"></div>
            </li>
			<?php endif; ?>
        </ul>
    </div><!-- topheader -->
	<script type="text/javascript">
		
		jQuery(document).ready(function(){
			jQuery('.dropbox').hide();
			jQuery(document).delegate(".views", "click", function(e){
				  jQuery.colorbox({width:"70%", href:this.href});
				  return false;
				  //event.preventDefault();
			});
		
		});

		
		function notify_today()
		{
			//alert('test');
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url("admin/admin_notification/today"); ?>',
				success: function(msg){
					jQuery(".notify_today").append(msg);
				}
			}); 
			return false;	
		}
		function approve_notify()
		{
			//alert('test');
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('admin/admin_notification/approval'); ?>',
				success: function(msg){
					jQuery(".approve_notify").append(msg);
				}
			});
			return false; 	
		}

		function message_today(val)
		{
			if(val=='admin')
			{		
				jQuery.ajax({
					type: "POST",
					url: '<?php echo site_url('admin/messages/tabview/0'); ?>',
					success: function(msg){
						jQuery(".message_admin").append(msg);
					}
				}); 
				return false;
				
			}	
			else if(val=='adv')
			{
				//alert('test');
				jQuery.ajax({
					type: "POST",
					url: '<?php echo site_url('advertiser/messages/tabview/0'); ?>',
					success: function(msg){
						jQuery(".message_adv").append(msg);
					}
				});
				return false; 
			}	
			else if(val=='pub')
			{
				//alert('test');
				jQuery.ajax({
					type: "POST",
					url: '<?php echo site_url('publisher/messages/tabview/0'); ?>',
					success: function(msg){
						jQuery(".message_pub").append(msg);
					}
				}); 
				return false;
			}	
			else
			{
				return false;
			}
		}
   </script>
