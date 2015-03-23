<?php

/* Header */ 
$this->load->view("includes/header");

/* Menu */ 
$this->load->view("includes/menu");

/* Account */ 
$this->load->view("includes/account");

/* Sidebar */ 
$this->load->view("includes/sidebar");

?>

<div class="maincontent">
	
    <div class="breadcrumbs">
    	<?php echo $breadcrumb; ?>
		
    </div><!-- breadcrumbs -->

    <div class="left">
    
    	<h1 class="pageTitle">My Account</h1>
    	
        <form id="form" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Account Information</legend>
                    
					<p>
                    	<label for="name">Username</label>
                        <label for="username"  name="username">nachimuthu</label>
                    </p>
					<br/>
                    <p>
                    	<label for="name">Name</label>
                        <input type="text" name="name"  id="name" class="sf" />
                    </p>
                    
                    <p>
                    	<label for="email">Email</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
                    
                    <p>
                    	<label for="location">Address</label>
                        <textarea name="location" class="mf" cols="" rows=""></textarea>
                    </p>
                    
					<p>
                    	<label for="name">City</label>
                        <input type="text" name="name"  id="name" class="sf" />
                    </p>
					
					<p>
                    	<label for="name">State</label>
                        <input type="text" name="name"  id="name" class="sf" />
                    </p>
					
					<p>
                    	<label for="name">Country</label>
                        <select name="country" id="country">
                          <option value="">Choose One</option>
                          <option value="0">India</option>
                          <option value="1">Pakistan</option>
                          <option value="2">Srilanka</option>
                          <option value="3">Singapore</option>
                          <option value="4">Maleysia</option>
                          <option value="5">China</option>
                        		
                        </select>
                    </p>
					
					<p>
                    	<label for="name">Mobile No</label>
                        <input type="text" name="mobile"  id="mobile" class="sf" />
                    </p>
					
					<p>
                    	<label for="name">Zip Code</label>
                        <input type="text" name="Zip"  id="Zip" class="sf" />
                    </p>
					
                </fieldset>
				<br/>
				<fieldset>
                    <legend>Payment Information</legend>
                    
					<p>
                    	<label for="name">Paypal ID</label>
                        <input type="text" name="paypal_id"  id="paypal_id" class="sf" />
                    </p>
					
					<p>
                    	<label for="name">Account Type</label>
                        <select name="accounttype" id="accounttype">
                          <option value="">Choose One</option>
                          <option value="0">India</option>
                          <option value="1">Pakistan</option>
                          <option value="2">Srilanka</option>
                          <option value="3">Singapore</option>
                          <option value="4">Maleysia</option>
                          <option value="5">China</option>
                        		
                        </select>
                    </p>
					
					<p>
                    	<label for="name">Tax</label>
                        <input type="text" name="tax" id="tax" class="sf" />
                    </p>
					
                </fieldset>
				<br/>
				<fieldset>
                    <legend>Advertiser Report</legend>
                    
					<p>
                    	<input type="checkbox" name="language[]" value="0" /> &nbsp;&nbsp; 
						Email when a campaign is automatically activated/deactivated.						
                    </p>
					
					<p>
						<input type="checkbox" name="language[]" value="0" /> &nbsp;&nbsp; 
						Email campaign delivery reports
					</p>
					
					<p>
                    	<label for="name">No. of days b/w campaign delivery reports</label>
                        <input type="text" name="paypal_id"  id="paypal_id" class="sf" />
                    </p>
					
                	<p>
                	   	<button>Submit</button>
                	</p>
				</fieldset>
				
            </div><!--form-->
            
        
        </form>
        
    </div><!--fullpage-->
    
    <br clear="all" />
    
</div><!--maincontent-->

<br />
<?php
/* Footer */ 
$this->load->view("includes/footer");
?>
