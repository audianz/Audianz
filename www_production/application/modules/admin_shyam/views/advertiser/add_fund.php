<script>
	jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
		jQuery("#adv_add_fund").validationEngine();
	});
	function Decimalcheck(field, rules, i, options)
	{
		var number = field.val();
		 var decimal= /^[0-9]+\.[0-9]{2}$/;
		 var str=number.split('.');
			if(typeof(str[1]) != "undefined" && str[1] !== null)
			{
				var len=str[1];
				var len_1=len.length;
				if(len_1 > 2)
				{	
					if ((!decimal.test(number)))
					{
						return '<?php echo "Cannot have more than 2 decimals." ?>'; 
					}
				}
			}
	}
</script>
 <?php if($this->session->userdata('error_message') != ''): ?>
	 <div class="notification msgerror">
         <a class="close"></a>
         <p><?php echo $this->session->userdata('error_message'); ?></p>
     </div>
<?php $this->session->unset_userdata('error_message'); endif; ?>
<h1 class="pageTitle"><?php echo $this->lang->line('label_inventory_advertisers_page_title'); ?></h1>
<form class="formular" id="adv_add_fund" name"adv_add_fund" action="<?php echo site_url('admin/inventory_advertisers/process/fund/add'); ?>" method="post">
        	<div class="form_default">
                <fieldset>
                    <legend><?php echo $this->lang->line('label_inventory_advertiser_add_fund'); ?></legend>
                    <p>
                    	<label for="name"><?php echo  $this->lang->line('label_amount'); ?></label>
                        <input type="text" alt="<?php echo $this->lang->line('label_enter_valid_amount'); ?>" value="<?php echo form_text(set_value('amount')); ?>" class="validate[required,custom[number],min[1],funcCall[Decimalcheck]]" name="amount"  id="amount"  />
                    </p>
                    <p>
                    	<input type="hidden" value="<?php echo $sel_advertiser; ?>" name="sel_advertiser" id="sel_advertiser" />
						<button type="submit"><?php echo  $this->lang->line('label_update'); ?></button>
						<button onclick="javascript: goToList();" style="margin-left:10px;" type="button"><?php echo  $this->lang->line('label_cancel'); ?></button>
                    </p>
                </fieldset>
            </div><!--form-->
</form>
<script>
function goToList(){
			document.location.href='<?php echo site_url("admin/inventory_advertisers"); ?>';
	}

</script>
