<script type="text/javascript">
jQuery(document).ready(function(){
	
	//////////// FORM VALIDATION /////////////////
	jQuery("#test").validate({
		rules: {
			name: "required",
			email: {
				required: true,
				email: true,
			},
			occupation: "required"
		},
		messages: {
			name: "Please enter your name",
			email: "Please enter a valid email address",
			occupation: "Please select your occupation"
		}
	});

});	
</script>

    	
       <form id="test" action="" method="post">
        
        	<div class="form_default">
                <fieldset>
                    <legend>Advertiser Info</legend>
                    
                    <p>
                    	<label for="name">Name</label>
                        <input type="text" name="name"  id="name" class="sf" />
                    </p>
                    
                    <p>
                    	<label for="email">Email</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
					
					<p>
                    	<label for="email">Username</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
					<p>
                    	<label for="email">Password</label>
                        <input type="password" name="email"  id="email" class="sf" />
                    </p>
					<p>
                    	<label for="email">Confirm Password</label>
                        <input type="password" name="email"  id="email" class="sf" />
                    </p>	
					 <p>
                    	<label for="location">Address</label>
                        <textarea name="location" class="mf" cols="" rows=""></textarea>
                    </p>
					<p>
                    	<label for="email">City</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
                    <p>
                    	<label for="email">State</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
					<p>
                    	<label for="email">Country</label>
                        <select>
							<option>India</option>
							<option>USA</option>
							<option>UK</option>
							<option>China</option>
							<option>Pakistan</option>
						</select>
                    </p>
                    
                   <p>
                    	<label for="email">Mobile No</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
                    <p>
                    	<label for="email">Zip Code</label>
                        <input type="text" name="email"  id="email" class="sf" />
                    </p>
                    
                    <p>
                    	<button>Submit</button>
                    </p>
                    
                </fieldset>
            </div><!--form-->
            
        
        </form>
    
 