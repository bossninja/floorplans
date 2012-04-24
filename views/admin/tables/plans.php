        <table>
		<thead>
			<tr>
				<th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')); ?></th>
				<th>Title</th>
				<th class="collapse">Lease Price</th>
				<th class="collapse">Purchase Price</th>				
				<th>Status</th>
				<th width="180"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
                <tbody>
                    <?php foreach ($floorplan_list as $plan) : ?>
                        <tr>
                                <td><?php echo form_checkbox('action_to[]', $plan->floorplan_id); ?></td>
                                <td><?php echo $plan->title; ?></td>
                                <td class="collapse"><?php echo $plan->lease_price; ?></td>
                                <td class="collapse"><?php echo $plan->purchase_price; ?></td>                                
                                <td><?php echo $plan->status; ?></td>
                                <td>
                                        <?php echo anchor('admin/floorplan/preview/' . $plan->floorplan_id, lang($plan->status == 'enabled' ? 'global:view' : 'global:preview'), 'rel="modal-large" class="iframe btn green" target="_blank"'); ?>
                                        <?php echo anchor('admin/floorplan/edit/' . $plan->floorplan_id, lang('global:edit'), 'class="btn orange edit"'); ?>
                                        <?php echo anchor('admin/floorplan/delete/' . $plan->floorplan_id, lang('global:delete'), array('class'=>'confirm btn red delete')); ?>
                                </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
        </table>        