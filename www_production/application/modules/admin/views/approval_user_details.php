
	

<div id="approval_view_popup">
    	<h1 class="pageTitle"><?php echo ucfirst($user_details->contact_name); ?></h1>
    
        <!-- Your Working location -->
		<div class="sTableOptions2">
        	<h4><b><?php echo ucfirst($user_details->contact_name); ?> <?php echo $this->lang->line('label_full_details');?></b></h4>	            
        </div>
		<table cellspacing="0" cellpadding="0" width="100%" id="userlist" class="sTable2">
            <thead>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $this->lang->line('label_advertiser_name');?> </td>
                    <td><?php echo ucfirst($user_details->contact_name);?> </td>
                  </tr>
                <tr class="even">
                	<td><?php echo $this->lang->line('label_advertiser_email');?> </td>
                    <td><?php echo $user_details->email_address;?> </td>    
				</tr>
				<tr>
                  	<td><?php echo $this->lang->line('label_advertiser_username');?> </td>
                    <td><i><?php echo $user_details->username;?> </i></td>
                </tr>
                <tr class="even">
                    <td><?php echo $this->lang->line('label_advertiser_contact_address');?> </td>
                    <td>
							<?php echo $user_details->address;?> 
							<br/>
							<?php echo $user_details->city; ?> , <?php echo $user_details->state; ?>  <?php echo $user_details->postcode; ?>
							<br/>
							<?php echo $user_details->	country; ?>
					</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('label_advertiser_mobile_no');?> </td>
                    <td><?php echo $user_details->mobileno;?> </td>
                </tr>
				 <tr class="even">
                    <td><?php echo $this->lang->line('label_advertiser_added_date');?> </td>
                    <td><b><?php echo date_format(date_create($user_details->date_added),'jS F Y'); ?></b> </td>
                </tr>
				<?php 
				if($user_details->site_url !=''): ?>
				 <tr>
                    <td><?php echo $this->lang->line('label_publisher_site_url');?> </td>
                    <td><b><?php echo $user_details->site_url; ?></b> </td>
                </tr>
				<?php endif; ?>
				
            </tbody>
        </table>
</div>

