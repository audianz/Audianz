<?php 
	
	$clientid = $this->session->userdata('session_advertiser_id'); //'14';//Pass the session advertiser id value
	
	$total_ban_pend_notify	=	$this->mod_adv_notifications->get_total_count_pend_banners($clientid);
		
?>

<div class="topheader">
        <ul class="notebutton">
		<?php if($this->session->userdata('session_user_type') =='ADVERTISER') { ?>
            <li class="note">
                <a href="javascript:void(0);" onclick="message_notify();" title="<?php echo $this->lang->line('label_message_notification_title');?>">
                    <span class="wrap">
                        <span class="thicon msgicon"></span>
						<?php $count	=$this->mod_suggestions->get_tabread('0', $this->session->userdata('session_advertiser_id')); ?>
						<?php if($count >0) : ?><span class="count"><?php echo $count; ?></span><?php endif; ?>
                    </span>
                </a>
		<div class="message_adv"></div>
            </li>
			<?php } ?>
			
			
            <li class="note">
            	<a href="javascript:void(0);" onclick="notify_today();" title="<?php echo $this->lang->line('label_banners_notification_title'); ?>">
                	<span class="wrap">
                    	<span class="thicon infoicon"></span>
						<?php if($total_ban_pend_notify>0 && isset($total_ban_pend_notify)):?>
                        <span class="count"><?php echo $total_ban_pend_notify; ?></span>
						<?php endif; ?>
                    </span>
                </a>
		<div class="notify_today"></div>
            </li>
        </ul>
    </div><!-- topheader -->
	
		<script type="text/javascript">
		jQuery(document).ready(function(){
	
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
				url: '<?php echo site_url('advertiser/advertiser_notification'); ?>',
				success: function(msg){
					jQuery(".notify_today").append(msg);
				}
			}); 	
		}
		function message_notify()
		{
			//alert('test');
			jQuery.ajax({
				type: "POST",
				url: '<?php echo site_url('advertiser/messages/tabview/0'); ?>',
				success: function(msg){
					jQuery(".message_adv").append(msg);
				}
			}); 	
		}
   </script>
