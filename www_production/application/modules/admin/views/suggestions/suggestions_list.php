  	 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
	 <?php if($page_title != ''): ?><h1 class="pageTitle"><?php echo $page_title; ?></h1><?php endif; ?>   
     
	<?php  if($this->session->flashdata('message') !=''): ?>
		 <div class="notification msgsuccess"><a class="close"></a><p><?php echo $this->session->flashdata('message'); ?></p></div>		
	<?php endif; ?>
		<form id="frmSuggestionsList" action="" method="post" >
    	<table cellpadding="0" cellspacing="0" class="sTableHead" width="100%">
        	<colgroup>
            	<col class="head0" width="5%" />
                <col class="head1" width="20%" />
                <col class="head0" width="50%" />
				<col class="head1" width="15%" />
				<col class="head0" width="10%" />
            </colgroup>
            <tr>
            	<td align="center"><?php echo $this->lang->line('label_suggestions_reply_inc'); ?></td>
                <td><?php echo $this->lang->line('label_suggestions_reply_sender'); ?></td>
				<td><?php echo $this->lang->line('label_suggestions_reply_subject'); ?></td>
				<td><?php echo $this->lang->line('label_suggestions_reply_date'); ?></td>
                <td align="center"><?php echo $this->lang->line('label_action'); ?></td>
            </tr>
        </table>
        
        <div class="sTableWrapper">
            <table cellpadding="0" cellspacing="0" class="sTable" id="userlist" width="100%">
                <colgroup>
                    <col class="con0" width="5%" />
                    <col class="con1" width="20%" />
					<col class="con0" width="50%" />
					<col class="con1" width="15%" />
					<col class="con0" width="10%" />
                </colgroup>
				<?php 
					if(count($rs) > 0 && $rs !=''):
					
						$i=$offset;
						foreach($rs as $row):
						if($row->type =='ADVERTISER') { $client	=$this->mod_suggestions->get_advertiser_info($row->suggestion_sender); $sender	=@$client[0]->clientname; }
						else if($row->type =='TRAFFICKER') { $client =$this->mod_suggestions->get_publisher_info($row->suggestion_sender); $sender	=@$client[0]->contact; }
						else { $sender	='MANAGER'; }
				?>
				<tr>
					<td align="center"><?php echo $i++; ?></td>
					<td><?php echo $sender; ?></td>
					<td><?php echo view_text($row->subject); ?></td>
					<td><?php echo date("F d, Y", strtotime($row->suggestion_date)); ?></td>
					<td align="center"><a href="<?php echo site_url('admin/suggestions/view/'.$row->suggestion_id); ?>" class="views">
					<?php echo $this->lang->line("label_suggestions_reply_read"); ?></a> &nbsp;
					<?php $chk =$this->mod_suggestions->get_reply_status($row->suggestion_id); if($chk ==0) : ?>
					 <a href="<?php echo site_url('admin/suggestions/reply/'.$row->suggestion_id); ?>" class="views"><?php echo $this->lang->line("label_suggestions_reply_reply"); ?></a>
					 <?php endif; ?>
					 </td>	
			  </tr>
  			  <?php	endforeach; else: ?>
				<tr>
                    <td align="center" colspan="7"><?php echo $this->lang->line("label_suggestions_norecords"); ?></td>
                </tr>
				<?php endif; ?>
            </table>
	        </div><!--sTableWrapper-->
			</form>
