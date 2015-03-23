<h1><?php echo $this->lang->line('label_invocation');?></h1>
<br />
<?php 
    //$zonid =($this->uri->segment(4) !='')?$this->uri->segment(4):0;
 	$qry   ="SELECT Z.zonename AS Zone, A.name AS Name FROM ox_zones Z, ox_affiliates A WHERE A.affiliateid= Z.affiliateid AND Z.zoneid=".$zoneid." LIMIT 1";
	$query =$this->db->query($qry);
	
	if($query->num_rows>0)
	{
	  $rs =$query->row_array();
	}
	else
	{
	  $rs ="";
	}	
?>
<script type="text/javascript">

function selectall(txt)
{
	txt.focus();
	txt.select();
}

function tagrefresh() 
{
	jQuery('#invocationform_image').submit();		
}

</script>
<!-- IMAGE TAG --->
<?php
		/* Common */
			$list['zoneid']				=$zoneid;
			$list['target']				=$this->input->post("select");
			$list['source']				=$this->input->post("source");
			$list['refresh']			=$this->input->post("refresh");
			$list['source_sel']			=str_replace(" ", "+", $list['source']);
			$list['party']				=$this->input->post("party");
			$list['comments']			=($this->input->post("comments") =='')?1:$this->input->post("comments");
			$list['val']				="aac".$zoneid."674";
		/* Common */
		
		/* JS Tag */
			$list['banner']				=$this->input->post("banner");
			$list['text']				=$this->input->post("text");
			$list['campaign']			=$this->input->post("campaign");
			$list['charset']			=$this->input->post("charset");			
		/* JS Tag */
		
		/* Image Tag */
			$list['cachebuster']		=$this->input->post('cachebuster');
		/* Image Tag */
?>

<div id="java">
<form name="invocationform_image" id="invocationform_image" method="post" action="" style="padding-left:50px;">
		<?php $this->load->view('zones/tags/image.php', $list); ?>
		<br />
		<?php $this->load->view('zones/tags/imagetag.php', $list); ?>
</form>
</div>
<br />
<button style="margin-left:85px;" type="button" class="button button_blue" onclick="tagrefresh()"><?php echo $this->lang->line("label_refresh"); ?></button>
<button type="button" class="button button_blue" onclick="javascript: goToList();" ><?php echo $this->lang->line('label_cancel'); ?></button>
<br />
<br />
<script type="text/javascript">
		function goToList() { document.location.href='<?php echo site_url('publisher/zones'); ?>'; }
</script>
