<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#camptab').dataTable( {
                "sSortColumn": "1",
                "sPaginationType": "full_numbers"
	});

});

function pausecamps()
{
    var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
    if(camparr!='')
    {
            jConfirm('<?php echo $this->lang->line('label_camp_pause_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
                    if(r)
                    {
                            jQuery.post('<?php echo site_url('advertiser/campaigns/pause_campaign'); ?>', {'campaignarr[]': camparr}, function(response) {
                            //alert(response)
                            location.reload();

                            });
                    }
                    else
                    {
                        document.getElementById('checkall').checked = false;
                        if(!jQuery(this).is(':checked')) {
                                jQuery('.sTable input[type=checkbox]').each(function(){
                                        jQuery(this).attr('checked',false);
                                        jQuery(this).parents('tr').removeClass('selected');
                                });
                        }
                    }
            });

    }

    else
    {
            jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>');
    }
}

function runcamps()
{
    var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
    if(camparr!='')
    {
            jConfirm('<?php echo $this->lang->line('label_camp_run_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
                    if(r)
                    {
                            jQuery.post('<?php echo site_url('advertiser/campaigns/run_campaign'); ?>',{'campaignarr[]': camparr}, function(response){
                            location.reload();

                            });
                    }
                    else
                    {
                        document.getElementById('checkall').checked = false;
                        if(!jQuery(this).is(':checked')) {
                                jQuery('.sTable input[type=checkbox]').each(function(){
                                        jQuery(this).attr('checked',false);
                                        jQuery(this).parents('tr').removeClass('selected');
                                });
                        }
                    }
            });
    }
    else
    {
            jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>');
    }
}

function delcamps()
{
    var camparr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
    if(camparr!='')
    {
        jConfirm('<?php echo $this->lang->line('label_camp_delete_confirm'); ?>','<?php echo $this->lang->line('label_inventory_campaign_page_title'); ?>',function(r){
            if(r)
            {
                jQuery.post('<?php echo site_url('advertiser/campaigns/delete_campaign'); ?>', {'campaignarr[]': camparr}, function(response) {
                location.reload();
                });
            }
            else
            {
                document.getElementById('checkall').checked = false;
                if(!jQuery(this).is(':checked')) {
                    jQuery('.sTable input[type=checkbox]').each(function(){
                            jQuery(this).attr('checked',false);
                            jQuery(this).parents('tr').removeClass('selected');
                    });
                }
            }
        });
    }
    else
    {
        jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>');
    }
}
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>

<?php if($this->session->flashdata('form_add_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_add_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('form_edit_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_edit_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('form_delete_success') != ""): ?>
    <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php endif;

if($this->session->flashdata('delete_campaign') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_delete_success'); ?></p></div>
<?php
endif;

if($this->session->flashdata('form_target_success') != ""): ?>
	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('form_target_success'); ?></p></div>
<?php
endif;

if($this->session->flashdata('pause_campaign') != ""): ?>
<div class="notification msginfo"> <a class="close"></a> <p><?php echo $this->session->flashdata('pause_campaign'); ?></p></div>
<?php
endif;

if($this->session->flashdata('run_campaign') != ""): ?>
<div class="notification msginfo"> <a class="close"></a> <p><?php echo $this->session->flashdata('run_campaign'); ?></p></div>
<?php
endif;

if($this->session->flashdata('camp_error') != ""): ?>
	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('camp_error'); ?></p></div>
<?php
endif;
?>

          <h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_campaign_page_title');?></h1>
          <br/>
          <div id="buttons" style="width:99%;height:40px;">
              <div style="width:45%;float:left;">
                    <a href="javascript:void(0);" onclick="runcamps();" class="iconlink"><img src="<?php echo base_url(); ?>assets/images/icons/play.jpeg" class="mgright5" alt="" /> <span> <?php echo $this->lang->line('label_run_campaign');?></span></a>
                    <a href="javascript:void(0);" onclick="pausecamps();" class="iconlink"><img src="<?php echo base_url(); ?>assets/images/icons/pause.png" class="mgright5" alt="" /> <span> <?php echo $this->lang->line('label_pause_campaign');?></span></a>
                    <a href="javascript:void(0);" onclick="delcamps();" class="iconlink"><img src="<?php echo base_url(); ?>assets/images/icons/small/white/close.png" class="mgright5" alt="" /> <span><?php echo $this->lang->line('label_delete');?> </span></a>
              </div>
              <div style="width:44%;float:right;text-align: right;">
                <a href="<?php echo site_url('advertiser/campaigns/add'); ?>" class="iconlink"><img src="<?php echo base_url(); ?>assets/images/icons/small/white/plus.png" class="mgright5" alt="" /> <span> <?php echo $this->lang->line('label_inventory_add_campaign');?></span></a>
              </div>
          </div>
          
          <table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="camptab">
            <thead>
                <tr>
                    <th class="head1"><input type="checkbox" class="checkall" id="checkall" /></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_advertiser_campaign_date');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign_status');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_advertiser_campaign_banners');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign_model');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_advertiser_campaign_price');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign_daily_budget');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_advertiser_campaign_impr');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign_clicks');?></th>
                    <th class="head1"><?php echo $this->lang->line('label_advertiser_campaign_ctr');?></th>
                    <th class="head0"><?php echo $this->lang->line('label_advertiser_campaign_spend');?></th>
                </tr>
            </thead>
            <colgroup>
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            	<col class="con0" />
                <col class="con1" />
            	<col class="con0" />
                <col class="con1" />
            	<col class="con0" />
                <col class="con1" />
            	<col class="con0" />
            </colgroup>
          
            <tbody>
                <?php 
                if(count($camps)>0):
                    foreach($camps as $camp):
                 ?>
                    <tr class="gradeX">
                        <td class="con1"><input type="checkbox" id="<?php echo $camp->campaignid; ?>"/></td>
                        <td class="con0"><?php echo $camp->campaignname; ?>
                        <br/>
                        <a href="<?php echo site_url('advertiser/campaigns/edit/'.$camp->campaignid); ?>" ><?php echo $this->lang->line('label_edit');?></a> -
                        <a href="<?php echo site_url('advertiser/campaigns/duplicate/'.$camp->campaignid); ?>"><?php echo $this->lang->line('label_advertiser_campaign_duplicate');?></a>
                        </td>
                        <td class="con1"><?php echo date('M d,Y', strtotime($camp->activate_time)); ?></td>
                        <td class="con0"><?php echo ucfirst($this->mod_campaigns->get_campaign_status($camp->status)); ?></td>
                        <td class="center con1"><a href="<?php echo site_url('advertiser/banners/'.$camp->campaignid); ?>"><?php echo $this->lang->line('label_advertiser_campaign_banners');?></a> / <a href="<?php echo site_url('advertiser/campaigns/'.$camp->campaignid); ?>"><?php echo $this->lang->line('label_advertiser_campaign_targeting');?></a></td>
                        <td class="center con0"><?php echo $this->mod_campaigns->get_revenue_type($camp->revenue_type); ?></td>
                        <td class="center con1">$<?php echo round($camp->revenue, 2); ?></td>
                        <!-- Campaign Amount -->
                        <?php if($acBal>0): 
                            $campAmt = $this->mod_campaigns->get_campaign_amount($camp->campaignid);
                        else:
                            $campAmt = 0;
                        endif; ?>

                        <!-- Daily Budget -->
                        <?php
                            $dailyBud = $this->mod_campaigns->get_campaign_budget($advertiser,$camp->campaignid);
                            $totAmt = $dailyBud-$campAmt;
                            if($acBal>0):
                                $dailyBalance = $totAmt;
                            else:
                                $dailyBalance = $dailyBud;
                            endif;
                        ?>
                        <td class="center con0">$<?php echo $dailyBalance; ?></td>
                        <?php
                            /* Budget Impressions */
                            $buck_imp = $this->mod_campaigns->get_budget_impressions($advertiser,$camp->campaignid);
                            /* Budget Clicks */
                            $buck_cli = $this->mod_campaigns->get_budget_clicks($advertiser,$camp->campaignid);
                            /* Common Stats */
                            $common_stat = $this->mod_campaigns->get_common_stats($camp->campaignid);
                            $impressions = $common_stat[0]->impress+$buck_imp[0]->count;
                            $clicks = $common_stat[0]->clicks+$buck_cli[0]->count;
                            if($impressions!=''&&$clicks!=''):
                                $ctr = number_format((($clicks/$impressions)*100), 2);
                            else:
                                $ctr = 0;
                            endif;
                        ?>
                        <td class="center con1"><?php echo ($impressions!='')?$impressions:0; ?></td>
                        <td class="center con0"><?php echo ($clicks!='')?$clicks:0; ?></td>
                        <td class="center con1"><?php echo $ctr; ?>%</td>
                        <td class="center con0">$<?php echo $campAmt; ?></td>
                    </tr>
                <?php endforeach;
                endif; ?>
	        </tbody>
      </table>
