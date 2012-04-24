<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'floorplans';
	
        /**
	 * Array that contains the validation rules
	 * @access protected
	 * @var array
	 */
        protected $validation_rules = array(
            array(
			'field' => 'title',
			'label' => 'Title',
			'rules' => 'required'
		 ),
            array(
			'field' => 'features',
			'label' => 'Features',
			'rules' => 'required'
		 ),
            array(
			'field' => 'lease_price',
			'label' => 'Lease Price',
			'rules' => ''
		 ),
            array(
			'field' => 'purchase_price',
			'label' => 'Purchase Price',
			'rules' => ''
		 ),
        );
        
	/**
	 * Constructor method
	 *
	 * @return void
	 */
	function __construct()
	{	
		// Call the parent's constructor method
		parent::__construct();		
		
		// load Language file
		$this->lang->load('floorplans');
                $this->load->model('floorplan_m');
                $this->load->library('form_validation');
	}
        
        function index()
        {
            $this->list_floorplans();
            //$this->template->build('admin/index');
        }
        
        function create()
        {
            $this->form_validation->set_rules($this->validation_rules);
            
            if($this->form_validation->run())
            {
                $floorplan_id = $this->floorplan_m->insert(array(
                    'title'          => $this->input->post('title'),
                    'lease_price'    => $this->input->post('lease_price'),
                    'purchase_price' => $this->input->post('purchase_price'),
                    'features'       => $this->input->post('features')
                ));
                
                if($floorplan_id)
                {
                    $this->session->set_flashdata('success', $this->lang->line('floorplan_add_success'));
                    redirect('admin/floorplans');
                }
            }
            
            $this->template->build('admin/create');
        }
        
        function list_floorplans()
        {            
            $this->template
                    ->set('floorplan_list', $this->floorplan_m->get_all());
            
            $this->template->build('admin/index');
        }
}