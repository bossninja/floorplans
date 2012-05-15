<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Floorplan_features_m extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->_table = 'floorplan_features';
        $this->primary_key = 'floorplan_feature_id';
	}
}

?>