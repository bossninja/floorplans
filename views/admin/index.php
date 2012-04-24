<section class="title">
	<h4><?php echo lang('list_title'); ?></h4>
</section>

<section class="item">

<?php if ($floorplan_list) : ?>

<?php //echo $this->load->view('admin/partials/filters'); ?>

<div id="filter-stage">

	<?php echo form_open('admin/floorplans/action'); ?>

		<?php echo $this->load->view('admin/tables/plans'); ?>

	<?php echo form_close(); ?>
	
</div>

<?php else : ?>
	<div class="no_data"><?php echo lang('list_hasnt_plans'); ?></div>
<?php endif; ?>

</section>
