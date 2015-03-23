	 <?php if($this->session->flashdata('success_message') != ''): ?>
	 	<div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('success_message'); ?></p></div>
	 <?php endif; ?>
	 
	 <?php if($this->session->flashdata('error_message') != ''): ?>
	 	<div class="notification msgerror"><a class="close"></a><p><?php echo $this->session->flashdata('error_message'); ?></p></div>
	 <?php endif; ?>
	 
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     <?php endif; ?>   
  
     <form id="frmTrackerList" action="<?php //echo site_url('admin/inventory_advertisers/tracker_process/rem/'.$sel_advertiser_id); ?>" method="post" >
        <div class="sTableOptions"><?php echo $this->pagination->create_links(); ?></div><!--sTableOptions-->

		<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="10%" />
                <col class="head0" width="30%" />
                <col class="head1" width="10%" />
                <col class="head0" width="10%" />
            </colgroup>
			<tr>
            	<td align="center"><?php echo $this->lang->line('label_tracker_sno'); ?></td>
                <td><?php echo $this->lang->line('label_tracker_name'); ?></td>
                <td><?php echo $this->lang->line('label_tracker_desc'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_tracker_status'); ?></td>
				<td align="center"><?php echo $this->lang->line('label_tracker_type'); ?></td>
            </tr>
        </table>
        
        <div  class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="30%" />
                    <col class="con1" width="10%" />
                    <col class="con0" width="10%" />
                </colgroup>
				<?php 
					$i=1;
					if(count($trackers_list) > 0 AND $trackers_list != FALSE ):
					foreach($trackers_list as $row):
				?>
				<tr>
					<td align="center"><?php echo $i++; ?></td>
					<td><?php echo view_text($row->trackername); ?></td>
					<td><?php echo view_text($row->description); ?></td>
					<td align="center"><?php echo $row->tracker_status; ?></td>
					<td align="center"><?php echo $row->tracker_type; ?></td>
				</tr>
				<?php
					endforeach;
					else:
				?>
				<tr>
                    <td align="center" colspan="6"><?php echo $this->lang->line("label_campaigns_trackers_record_not_found"); ?></td>
                </tr>
				<?php endif; ?>
		 </table>
		   </div><!--sTableWrapper-->
	     </form>
