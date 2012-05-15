<h1>List Plans</h1>
{{floorplans:lists limit="1" page="0"}}<br/>

	<p>Title: {{title}}</p>

	<p>Features:</p>
	{{floorplans:features plan_id=floorplan_id}}
		{{name}}<br />
	{{/floorplans:features}}

	{{floorplans:gallery folder_id=folder_id}}
		<?php echo img(array('src' => site_url('files/thumb/{{id}}/80/80'), 'alt' => '{{name}}')); ?>
	{{/floorplans:gallery}}

{{/floorplans:lists}}