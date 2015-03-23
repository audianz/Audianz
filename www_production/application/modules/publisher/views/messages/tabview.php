<div class="dropbox">
	<div class="messagelist">
	    <h4><?php echo $this->lang->line('label_suggestions_tabview_title'); ?></h4>
		<?php if(count($rs) > 0 && $rs !=''):  $class ='class="current"'; ?>
		 <ul>
		<?php foreach($rs as $row) : ?>
		<li <?php echo $class; ?>>
			<a href="<?php echo site_url('publisher/messages/messages_list'); ?>"><?php echo substr($row->suggestion_subject,0,30).'...'; ?></a>
			<span><?php echo substr($row->suggestion_content,0,30).'...'; ?></span>
		    <small><?php echo date("F d, Y", strtotime($row->suggestion_date)); ?></small>
		</li>
		<?php $class ='class="current"'; endforeach; ?>
		</ul>
		<?php else : ?>
		<ul><li class="current"><?php echo $this->lang->line("label_suggestions_new_norecords"); ?></li></ul>
		<?php endif; ?>
	  <!--  <div class="link"><a href="<?php //echo site_url('publisher/messages/messages_list'); ?>"><?php //echo $this->lang->line('label_suggestions_tabview_list'); ?></a></div> -->
	</div>
</div>
<?php exit; ?>
