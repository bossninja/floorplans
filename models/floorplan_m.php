<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Floorplan_m extends MY_Model 
{
    
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'floorplan';
        $this->primary_key = 'floorplan_id';
    }
    
}

?>
