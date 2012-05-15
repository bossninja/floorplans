<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PLugin_floorplans extends PLugin
{

	//--------------------------------------------------------------------------    

	public function lists()
	{
		$limit = $this->attribute('limit');
		$page  = $this->attribute('page');

		$this->load->model('floorplan_m');

		return $this->floorplan_m->get_plans(array('limit' => $limit, 'offset' => $page));
	}

	//--------------------------------------------------------------------------    

	public function gallery()
	{
		$folder_id = $this->attribute('folder_id');		

		$this->load->model('files/file_m');
		
		$this->file_m->order_by('sort', 'ASC');
		return $this->file_m->get_many_by('folder_id', $folder_id);
	}

	//--------------------------------------------------------------------------    

	public function features()
	{
		$plan_id = $this->attribute('plan_id');

		$this->load->model('floorplan_features_m');

		$features = $this->floorplan_features_m->get_many_by('floorplan_id', $plan_id);

		return $features ? $features : array();
	}

}
?>