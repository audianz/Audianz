<div style="padding:10px;margin:10px">
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
 <!-- Display page title dymically. page_title content must be initialized corresponding controller. -->
<br/>
	 <?php if($page_title != ''): ?>
		<h1 class="pageTitle"><?php echo $page_title; ?></h1>
     	<?php endif; ?>   
     
<br/>
	<table cellpadding="0" cellspacing="0" id="trackerslist" class="dyntable" width="100%">
          <thead>
                <tr>
            	 	<th class="head0">S.No.</td>
                	<th class="head1">Login Date</td>
			<th class="head0">Login Time</th>
			<th class="head1">Logout Time</th>
			<th class="head0">Ip Address</td>
                	<th class="head1">Country</td>
            	</tr>
	 </thead>
             <colgroup>
                <col class="con0" />
                <col class="con1" />
		<col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            </colgroup>
            
        
        <tbody>
   		     
				<?php 
					if(count($geo_locations_list) > 0 && $geo_locations_list!=''):
						$i=$offset;
						foreach($geo_locations_list as $row):
						?>
								<tr>
									<td class="con0"><?php echo $i++; ?></td>
									<td class="con1"><?php echo view_text(date("F j, Y",strtotime($row->logged_in))); ?></td>
									<td class="con0"><?php echo view_text($row->logged_in); ?></td>
									<?php if($row->logged_out): ?>
									<td class="con1"><?php echo view_text($row->logged_out); ?></td>
									<?php else: ?>
									<td class="con1">Not Logged out!</td>		
									<?php endif; ?>
									<td class="con0"><?php echo view_text($row->ip_address); ?></td>
									<td class="con1"><?php echo view_text($row->country); ?></td>
								</tr>
						<?php
						endforeach;
					else:
				?>
				<tr>
                    <td colspan="7">No Logged Entry</td>
                </tr>
				<?php
					endif;
				?>
		</tbody>

            </table>

<?php if(count($geo_locations_list) > 0 && $geo_locations_list!=''):?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#trackerslist').dataTable( {
		"sPaginationType": "full_numbers"
	});
	
});
</script>
<?php endif;?>
</div>
		  
