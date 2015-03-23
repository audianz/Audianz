<script type="text/javascript" src=<?php echo base_url()."assets/"; ?>"js/custom/general.js"></script>
<script type="text/javascript">
<?php if(count($banners_list) > 0 AND is_array($banners_list)): ?>
jQuery(document).ready(function() {

	jQuery('#banners_list').dataTable( {
		"sPaginationType": "full_numbers",
		"sSortableNone":"sorting_disabled"
	});

});

function pause_banners()
	{
                var bannerlist = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
                //alert(bannerlist);
		if(bannerlist!='')
		{
			jConfirm('<?php echo $this->lang->line('label_banner_pause_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                    jQuery.post('<?php echo site_url('advertiser/banners/pause_banner'); ?>', {'bannerarr[]': bannerlist}, function(response) {
					location.reload();

				});
                            }
                            else
                            {
				 jQuery('.checkall').attr('checked','false');
                                 jQuery('input[type=checkbox]').each(function(){
                            		jQuery(this).attr('checked',false);
					jQuery(this).parents('tr').removeClass('selected');

                                        });
                            }
                    });
		}
		else
		{
			jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
		}
	}


function run_banners()
	{
		var bannerarr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
                if(bannerarr!='')
		{
			jConfirm('<?php echo $this->lang->line('label_banner_run_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                    jQuery.post('<?php echo site_url('advertiser/banners/run_banner'); ?>', {'bannerarr[]': bannerarr}, function(response) {
					location.reload();

				});
                            }
                            else
                            {
				jQuery('.checkall').attr('checked','false');
                                 jQuery('input[type=checkbox]').each(function(){
                            		jQuery(this).attr('checked',false);
					jQuery(this).parents('tr').removeClass('selected');

                                        });
                            }
                    });
		}
		else
		{
			jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
		}
	}

function delete_banners()
	{
		var bannerarr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
               	if(bannerarr!='')
		{

                       jConfirm('<?php echo $this->lang->line('label_banner_delete_confirm'); ?>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>',function(r){
                            if(r)
                            {
                                        jQuery.post('<?php echo site_url('advertiser/banners/delete_banner'); ?>', {'bannerarr[]': bannerarr}, function(response) {
					location.reload();

				});
                            }
                            else
                            {
                                jQuery('.checkall').attr('checked','false');
                                 jQuery('input[type=checkbox]').each(function(){
                            jQuery(this).attr('checked',false);
                           
                    });
               
                            }
                    });

		}
                else
                {
                    jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
                }
	}
<?php endif; ?>

</script>

<script type="text/javascript">
function auth_display(){
 jAlert('<center><?php echo $this->lang->line('label_banner_zones_access_no_auth'); ?></center>','<?php echo $this->lang->line('label_inventory_banner_page_title'); ?>');
}
</script>

<?php if($this->session->flashdata('banner_add_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_add_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('banner_edit_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_edit_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('banner_delete_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('banner_delete_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('delete_campaign') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php
endif;

if($this->session->flashdata('pause_banner') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('pause_banner'); ?></p></div>
<?php
endif;

if($this->session->flashdata('run_banner') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('run_banner'); ?></p></div>
<?php
endif;

if($this->session->flashdata('banner_account_error') != ""): ?>
<div class="notification msgsuccess"> <a class="close"></a> <p><?php echo $this->session->flashdata('banner_account_error'); ?></p></div>
<?php
endif;

if($this->session->flashdata('banner_error') != ""): ?>
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('banner_error'); ?></p></div>
<?php
endif;

if($this->session->flashdata('banner_approval_error') != ""): ?>
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('banner_approval_error'); ?></p></div>
<?php
endif;
?>
		 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
			 <?php if($page_title != ''): ?>
			<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_banners_page_title'); ?></h1>
			 <?php endif; ?>

			 <?php
					if($this->session->flashdata('zones_success_message') != ""):
		   ?>
							<div class="notification msgsuccess"><a class="close"></a>
							<p><?php echo $this->session->flashdata('zones_success_message'); ?> </p>
							</div>
		<?php endif;?>
		<?php echo validation_errors();?>
      
		<div  style="height:40px;width:99%;padding:5px;">
			<div style="width:50%;float:left;">
	  			<a href="javascript:void(0);" onclick="run_banners();" class="iconlink"><img src="<?php echo base_url('assets'); ?>/images/icons/play.jpeg" class="mgright5" alt="" /> <span> <?php echo "Run";//$this->lang->line("label_pause"); ?> </span></a>
				<a href="javascript:void(0);" onclick="pause_banners();" class="iconlink"><img src="<?php echo base_url('assets'); ?>/images/icons/pause.png" class="mgright5" alt="" /> <span> <?php echo "Pause";//$this->lang->line("label_run"); ?> </span></a>
				<a href="javascript:void(0);" onclick="delete_banners();"id="delete_record" class="iconlink"><img src="<?php echo base_url('assets'); ?>/images/icons/small/white/close.png" class="mgright5" alt="" /> <span> <?php echo $this->lang->line("label_delete"); ?> </span></a>
	   		</div>
			<div style="width:49%;float:right;text-align:right;">
					<input type="hidden" id="campiagnid" value="<?php echo $campaignid; ?>" />
					<a href="<?php echo site_url("advertiser/campaigns"); ?>" class="iconlink2"><span><b>-></b>&nbsp;<?php echo $this->lang->line('completelabel_go_back');?></span></a>
					<a href="<?php echo site_url("advertiser/banners/add_banner/".$campaignid.""); ?>" class="iconlink"><img src="<?php echo base_url('assets'); ?>/images/icons/small/white/plus.png" class="mgright5" alt="" /> <span><?php echo $this->lang->line('label_advertiser_banners_new_banner');?></span></a>
	   		</div>
		</div>
	
        <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="banners_list">

            <thead>
                <tr>
                   <th class="head1"><input type="checkbox" class="checkall" /></th>
                   <th class="head0"><?php echo $this->lang->line("label_banner_name"); ?></th>
                   <th class="head1"><?php echo $this->lang->line("label_banner_status"); ?></th>
                   <th class="head0"><?php echo $this->lang->line("label_banner_url"); ?></th>
                   <th class="head1"><?php echo $this->lang->line("label_inventory_sizes"); ?></th>
                   <th class="head0"><?php echo $this->lang->line('label_impressions'); ?></th>
                   <th class="head1"><?php echo $this->lang->line('label_clicks'); ?></th>
				   <th class="head0"><?php echo $this->lang->line('label_conversions'); ?></th>
		   			<th class="head1"><?php echo $this->lang->line('label_ctr'); ?></th>
                   <th class="head0"><?php echo $this->lang->line("label_spend"); ?></th>
                   <th class="head1"><?php echo $this->lang->line("label_inventory_linked"); ?></th>
                   <th class="head0"><?php echo $this->lang->line("label_admin_status"); ?></th>
                </tr>
            </thead>
            <colgroup>
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
				<col class="con1"/>
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
				<col class="con0" />
                
            </colgroup>
            <tbody>

            <?php
                    if(isset($stat_data['reports_banners'][$campaignid]))
					$stat_list  =   $stat_data['reports_banners'][$campaignid];

                    if(count($banners_list) > 0):
                     if(is_array($banners_list)):
                                    foreach($banners_list as $row):

                                    if($row->master_banner != "-1"){
                                            $size = $row->width." X ".$row->height;
                                    }
                                    else
                                    {
                                            $size  = "--";
                                    }

            ?>

                <tr class="gradeX">
                    <td class="center con1"><input type="checkbox" id="<?php echo $row->bannerid; ?>" value="<?php echo $row->bannerid; ?>" name="check[]"/></td>
                    <td class="con0">
			<a title="<?php echo $this->lang->line('label_banner_edit_banner_details'); ?>" href="<?php echo site_url('advertiser/banners/edit_banner/'.$row->campaignid.'/'.$row->bannerid); ?>">
				<?php echo view_text($row->description); ?>
			</a>
                    </td>
                    
                    <?php if($row->status==0): ?>
                    <td class="center con1"><?php echo $this->lang->line('label_running'); ?></td>
                    <?php else: ?>
                    <td class="center con1"><?php echo $this->lang->line('label_paused'); ?></td>
                    <?php endif; ?> 
                   
                   <td class="con0"><?php echo view_text($row->url); ?></td>
                    <td class="con1" align=""><?php echo $size; ?></td>
                    <td class="con0"><?php echo (isset($stat_list[$row->bannerid]))?$stat_list[$row->bannerid]['IMP']:"0"; ?></td>
                    <td class="con1"><?php echo (isset($stat_list[$row->bannerid]))?$stat_list[$row->bannerid]['CLK']:"0"; ?></td>
					<td class="con0"><?php echo (isset($stat_list[$row->bannerid]))?$stat_list[$row->bannerid]['CON']:"0"; ?></td>
                    <td class="con1"><?php echo (isset($stat_list[$row->bannerid]))?number_format($stat_list[$row->bannerid]['CTR'],2,".",","):"0.00"; ?>%</td>
                    <td class="con0">$<?php echo (isset($stat_list[$row->bannerid]))?number_format($stat_list[$row->bannerid]['SPEND'],2):"0.00"; ?></td>
					<?php if($row->adminstatus == 0)
					{ 
					?>
							<td class="con1"><a href="<?php echo site_url("advertiser/banners/linked_zones/".$row->bannerid."/".$campaignid); ?>"><?php echo $this->lang->line('label_advertiser_banners_zones'); ?></a></td>
					<?php
					}
					else
					{
					?>
				   <td class="con1"><a onClick="auth_display()" href="javascript:void(0);"><?php echo $this->lang->line('label_advertiser_banners_zones'); ?></a></td>
					<?php
					}
					?>
	
                    
                    <?php if($row->adminstatus==0): ?>
                        <td class="con0" align=""><?php echo $this->lang->line('label_active'); ?></td>
                    <?php else: ?>
                        <td class="con1" align=""><?php echo $this->lang->line('label_inactive'); ?></td>
                    <?php endif; ?>
                    
                </tr>
				<?php
						endforeach;
						else:
				?>
				<tr class="gradeX">
                    <td colspan="12" class="con0">
							 <div class="sTableWrapper" style="padding:10px;" align="center"><?php echo $this->lang->line('label_no_banners'); ?></div>
					</td>

                </tr>
				<?php
					endif;
					endif;
				?>
            </tbody>

        </table>
		
