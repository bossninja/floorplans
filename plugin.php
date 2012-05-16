<?php defined('BASEPATH') OR exit('No direct script access allowed');

class PLugin_floorplans extends PLugin
{

	//--------------------------------------------------------------------------    

	public function lists()
	{
		$limit = $this->attribute('limit');
		$page  = $this->attribute('page');

		$this->load->model('floorplan_m');

		$floorplans = $this->floorplan_m->get_plans(array('limit' => $limit, 'offset' => $page));

		$data = array();
		$index = 0;

		foreach($floorplans as $f)
		{
			$data[] = array(
					'index' => $index++,
					'floorplan_id' => $f->floorplan_id,
					'title' => $f->title,
					'slug' => $f->slug,
					'lease_price' => $f->lease_price,
					'purchase_price' => $f->purchase_price,
					'status' => $f->status,
					'folder_id' => $f->folder_id
				);
		}

		return $data;
	}

	//--------------------------------------------------------------------------    

	public function gallery()
	{
		$folder_id = $this->attribute('folder_id');		

		$this->load->model('files/file_m');
		
		$this->file_m->order_by('sort', 'ASC');
		$files = $this->file_m->get_many_by('folder_id', $folder_id);

		$data = array();
		$index = 0;

		foreach($files as $f)
		{
			$data[] = array(
					'index' => $index++,
					'id' => $f->id,					
					'folder_id' => $f->folder_id,
					'user_id' => $f->user_id,
					'type' => $f->type,
					'name' => $f->name,
					'filename' => $f->filename,
					'description' => $f->description,
					'extension' => $f->extension,
					'mimetype' => $f->mimetype,
					'width' => $f->width,
					'height' => $f->height,
					'filesize' => $f->filesize,
					'date_added' => $f->date_added,
					'sort' => $f->sort
				);
		}

		return $data;
	}

	//--------------------------------------------------------------------------    

	public function features()
	{
		$plan_id = $this->attribute('plan_id');

		$this->load->model('floorplan_features_m');

		$features = $this->floorplan_features_m->get_many_by('floorplan_id', $plan_id);

		$data = array();
		$index = 0;

		foreach($features as $f)
		{
			$data[] = array(
					'index' => $index++,
					'floorplan_feature_id' => $f->floorplan_feature_id,
					'floorplan_id' => $f->floorplan_id,
					'name' => $f->name
				);
		}

		return $data;
	}

}
?>