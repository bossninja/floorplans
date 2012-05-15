<?php

class Floorplans extends Public_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('html');
		$this->load->model(array('floorplan_m', 'floorplan_features_m', 'files/file_m'));
	}

	public function index($plan=null)
	{		
		if(!is_null($plan))
		{
			if(is_numeric($plan))
			{
				$plan_data = $this->floorplan_m->get($plan);
			}
			else
			{
				$plan_data = $this->floorplan_m->get_by('slug', $plan);
			}

			if($plan_data)
			{				
				$plan_features = $this->floorplan_features_m->get_many_by('floorplan_id', $plan_data->floorplan_id);

				$this->file_m->order_by('sort', 'ASC');
				$plan_images = $this->file_m->get_many_by('folder_id', $plan_data->folder_id);				

				$this->data->plan_data = $plan_data;
				$this->data->plan_features  = $plan_features;
				$this->data->plan_images    = $plan_images;
			}
		}
		else 
		{
			show_404();
		}
		
	}

	public function test_plugin()
	{
		$this->template->build('plugin_test');
	}

}