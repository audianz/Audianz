
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#camptab').dataTable( {
	                 /*"sSortColumn": "1",*/
	                "bSort": false,
					/*"aaSorting": [],*/
	                "sPaginationType": "full_numbers"
		});
	
	});

	function add_network()
	{
		document.location.href='<?php echo site_url('admin/camp_optimisation/add_network'); ?>';
	}
	
	function delete_network()
	{
		var networkArr = jQuery("input:checkbox:checked").map(function(i, el) { return jQuery(el).attr("id"); }).get();
				if(networkArr!='')
				{
					
					jConfirm('<?php echo "Are you sure You want to delete these networks"; ?>','<?php echo "Manage Networks"; ?>',function(r)
					{
						if(r)
						{
							
							jQuery.post('<?php echo site_url('admin/camp_optimisation/remove_network'); ?>', {'arr[]':networkArr}, function(response) {
							location.reload();
							//alert(response); 
	                }); 
	            }
	            else
	            {
	                document.getElementById('checkall').checked = false;
	               
	                    jQuery('input[type=checkbox]').each(function(){
	                            jQuery(this).attr('checked',false);
	                            jQuery(this).parents('tr').removeClass('selected');
	                    });
	               
	            }
					});
				}
				else
			{
				jAlert('<center><?php echo $this->lang->line('label_no_item_selected'); ?></center>','<?php echo "Manage Networks"; ?>');
			} 
	}
	
	


</script>

<script
	type="text/javascript"
	src="<?php echo base_url(); ?>assets/js/custom/users.js"></script>

<?php if($this->session->flashdata('form_add_success') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('form_add_success'); ?>
	</p>
</div>
<?php endif;



if($this->session->flashdata('form_edit_success') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('form_edit_success'); ?>
	</p>
</div>
<?php endif;

if($this->session->flashdata('form_delete_success') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('form_delete_success'); ?>
	</p>
</div>
<?php endif;

if($this->session->flashdata('delete_campaign') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('form_delete_success'); ?>
	</p>
</div>
<?php
endif;

if($this->session->flashdata('form_target_success') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('form_target_success'); ?>
	</p>
</div>
<?php
endif;
if($this->session->flashdata('pause_campaign') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('pause_campaign'); ?>
	</p>
</div>
<?php
endif;
if($this->session->flashdata('block_run_campaign') != ""): ?>
<div class="notification msginfo">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('block_run_campaign'); ?>
	</p>
</div>
<?php
elseif($this->session->flashdata('complete_campaign') != ""): ?>
<div class="notification msgerror">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('complete_campaign'); ?>
	</p>
</div>
<?php
else:
if($this->session->flashdata('run_campaign') != ""): ?>
<div class="notification msgsuccess">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('run_campaign'); ?>
	</p>
</div>
<?php
endif;
endif;
if($this->session->flashdata('camp_error') != ""): ?>
<div class="notification msgerror">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('camp_error'); ?>
	</p>
</div>
<?php
endif;
if($this->session->flashdata('budget_completed') != ""): ?>
<div class="notification msgerror">
	<a class="close"></a>
	<p>
		<?php echo $this->session->flashdata('budget_completed'); ?>
	</p>
</div>
<?php
endif;
?>

<h1 class="pageTitle">
	<?php echo "Manage Campaign Optimisation"; ?>
</h1>
<br />
<div id="buttons" style="width: 99%; height: 40px;">
	<div style="width: 45%; float: left;">
		<a href="javascript:void(0);" onclick="add_network();"
			class="iconlink"><img
			src="<?php echo base_url(); ?>assets/images/icons/small/white/plus.png"
			class="mgright5" alt="" /> <span>
			Add Network
		</span> </a> 
		<a href="javascript:void(0);" onclick="delete_network();"
			class="iconlink"><img
			src="<?php echo base_url(); ?>assets/images/icons/small/white/close.png"
			class="mgright5" alt="" /> <span><?php echo $this->lang->line('label_delete_campaign');?>
		</span> </a>
	</div>
	
</div>

<table cellpadding="0" cellspacing="0" border="0" class="dyntable" id="camptab">
	<thead>
		<tr>
			<th class="head1"><input type="checkbox" class="checkall" id="checkall" /></th>
			<th class="head0">Id</th>
			<th class="head1">Network Type</th>
			<th class="head0">Network </th>
			<th class="head1"> Mix Percentage (%)</th>
			<th class="head0"> CPM Rate ($)</th>
		</tr>
	</thead>
	<colgroup>
		<col class="con1" width="5%" />
		<col class="con0" width="10%"  />
		<col class="con1" width="25%" />
		<col class="con0" width="25%" />
		<col class="con1"  width="18%" />
		<col class="con0"  width="17%" />
		
	</colgroup>

	<tbody>
		<?php 
		if(!empty($list)):
		foreach($list as $row):
		?>
		<tr class="gradeX">
			<td class="con1"><input type="checkbox" id="<?php echo $row->Mix_id; ?>" /></td>
			<td class="con0"><?php echo $row->Mix_id; ?>
			<br>
			<a href="<?php echo site_url('admin/camp_optimisation/edit_network_mix/'.$row->Mix_id); ?>" >Edit 
			</a> 
			 
			</td>
			<td class="con1"><?php echo $row->Network_type ?>
			</td>
			<td class="con0"><?php echo $row->Network ?>
			</td>
			
			<td class="con1"><?php echo $row->Mix_percent; ?>
			</td>
			
			<td class="con0"><?php echo $row->cpm_rate; ?>
			</td>

			
		</tr>
		<?php endforeach;
                endif; ?>
	</tbody>
</table>
